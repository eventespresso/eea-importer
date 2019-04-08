<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Answer;
use EE_Attendee;
use EE_Error;
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
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\services\commands\CommandBusInterface;
use EventEspresso\core\services\commands\CommandFactoryInterface;
use EventEspresso\core\services\commands\CommandInterface;
use EventEspresso\core\services\commands\CompositeCommandHandler;
use EventEspresso\core\services\options\JsonWpOptionManager;

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
     * @param CommandInterface $command
     * @return EE_Attendee
     * @throws EE_Error
     * @throws InvalidEntityException
     */
    public function handle(CommandInterface $command)
    {
        /** @var ImportCommand $command */
        if (!$command instanceof ImportCommand) {
            throw new InvalidEntityException(get_class($command), 'EventEspresso\AttendeeImporter\domain\services\commands\ImportCommand');
        }

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
        $line_item = EEH_Line_Item::create_ticket_line_item($transaction->total_line_item(), $command->getConfig()->getTicket());
        $transaction->total_line_item()->recalculate_total_including_taxes();
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
        EE_Registry::instance()->load_class('Registration_Processor')->toggle_incomplete_registration_status_to_default($registration, false);
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

        // @todo: Update that ticket and its datetime's ticket sales
        return null;
    }
}
