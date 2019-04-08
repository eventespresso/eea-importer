<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Answer;
use EE_Attendee;
use EE_Error;
use EE_Payment;
use EE_Registration;
use EE_Registration_Processor;
use EE_Registry;
use EEH_Line_Item;
use EEM_Question;
use EEM_Registration;
use EEM_Ticket;
use EEM_Transaction;
use EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigInterface;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\core\exceptions\EntityNotFoundException;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\commands\CommandBusInterface;
use EventEspresso\core\services\commands\CommandFactoryInterface;
use EventEspresso\core\services\commands\CommandInterface;
use EventEspresso\core\services\commands\CompositeCommandHandler;
use EventEspresso\core\services\options\JsonWpOptionManager;
use InvalidArgumentException;
use ReflectionException;
use RuntimeException;

/**
 * Class CreateAttendeeCommandHandler
 * generates and validates an Attendee
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 */
class ImportCommandHandler extends CompositeCommandHandler
{

    /**
     * @type EE_Registration_Processor $registration_processor
     */
    private $registration_processor;

    /**
     * @type EEM_Ticket $ticket_model
     */
    private $ticket_model;

    public function __construct(
        EE_Registration_Processor $registration_processor,
        EEM_Ticket $ticket_model,
        CommandBusInterface $command_bus,
        CommandFactoryInterface $command_factory
    )
    {
        parent::__construct($command_bus, $command_factory);
        $this->registration_processor = $registration_processor;
        $this->ticket_model = $ticket_model;
    }


    /**
     * @param CommandInterface $command
     * @return EE_Attendee
     * @throws EE_Error
     * @throws InvalidEntityException
     * @throws EntityNotFoundException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function handle(CommandInterface $command)
    {
        /** @var ImportCommand $command */
        if (!$command instanceof ImportCommand) {
            throw new InvalidEntityException(get_class($command), 'EventEspresso\AttendeeImporter\domain\services\commands\ImportCommand');
        }

        // Determine the ticket and event ID
        $ticket = $this->ticket_model->get_one_by_ID($command->getConfig()->getTicketId());

        // Create a transaction
        // @var $transaction EE_Transaction
        $transaction = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\transaction\CreateTransactionCommand',
                [
                    null,
                    []
                ]
            )
        );
        // Mark the transaction as complete eh.
        $transaction->save();
        $line_item = EEH_Line_Item::create_ticket_line_item($transaction->total_line_item(), $ticket);
        $transaction->total_line_item()->recalculate_total_including_taxes();

        // Create a payment and payment-registration entries.
        $payment = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\AttendeeImporter\domain\services\commands\ImportPaymentCommand',
                [
                    $transaction,
                    $command->csvRow(),
                    $command->getConfig()->getModelConfigs()->get('Payment')
                ]
            )
        );

        // Create a registration
        $registration = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\registration\CreateRegistrationCommand',
                [
                    $transaction,
                    $line_item,
                    1,
                    null
                ]
            )
        );

        // update registration based on other related details
        $this->registration_processor->toggle_incomplete_registration_status_to_default(
            $registration,
            false
        );
        $this->registration_processor->toggle_registration_status_for_default_approved_events(
            $registration,
            false
        );
        $this->registration_processor->toggle_registration_status_if_no_monies_owing(
            $registration,
            false,
            [
                'payment_updates' => $payment instanceof EE_Payment,
                'last_payment'    => $payment,
            ]
        );

        $attendee = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\AttendeeImporter\domain\services\commands\ImportAttendeeCommand',
                [
                    $registration,
                    $command->csvRow(),
                    $command->getConfig()->getModelConfigs()->get('Attendee')
                ]
            )
        );

        // Save the registration, and assign it to the attendee
        $registration->save(
            [
                'ATT_ID' => $attendee->ID()
            ]
        );

        $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\AttendeeImporter\domain\services\commands\ImportAnswersCommand',
                [
                    $registration,
                    $command->csvRow()
                ]
            )
        );

        // @todo: Update that ticket and its datetime's ticket sales
        return null;
    }
}
