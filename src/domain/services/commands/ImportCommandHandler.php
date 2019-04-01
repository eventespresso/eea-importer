<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Answer;
use EE_Attendee;
use EE_Error;
use EE_Registration;
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
     * @var ImportCsvAttendeesConfig
     */
    private $config;
    /**
     * @var JsonWpOptionManager
     */
    private $option_manager;

    public function __construct(
        CommandBusInterface $command_bus,
        CommandFactoryInterface $command_factory,
        ImportCsvAttendeesConfig $config,
        JsonWpOptionManager $option_manager
    ) {
        parent::__construct($command_bus, $command_factory);
        $this->config = $config;
        $this->option_manager = $option_manager;
    }


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

        // Determine the ticket and event ID
        $ticket = EEM_Ticket::instance()->get_one_by_ID($this->config->getTicketId());

        // Create a transaction
        $txn = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\transaction\CreateTransactionCommand',
                [
                    null,
                    []
                ]
            )
        );
        // Mark the transaction as complete eh.
        $txn->save(
            [
                'STS_ID' => EEM_Transaction::complete_status_code
            ]
        );
        $line_item = \EEH_Line_Item::create_ticket_line_item($txn->total_line_item(), $ticket);
        // Create a registration
        $reg = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\registration\CreateRegistrationCommand',
                [
                    $txn,
                    $line_item,
                    1,
                    null,
                    EEM_Registration::status_id_approved
                ]
            )
        );
        $attendee = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                '\EventEspresso\AttendeeImporter\domain\services\commands\ImportAttendeeCommand',
                [
                    $reg,
                    $command->csvRow()
                ]
            )
        );

        // Save the registration, and assign it to the attendee
        $reg->save(
            [
                'ATT_ID' => $attendee->ID()
            ]
        );

        // @todo: Create a payment

        // @todo: Create a payment-registration entry

        // @todo: Create line items

        // @todo: Update that ticket and its datetime's ticket sales
        foreach ($this->config->getQuestionMapping() as $question_id => $csv_column) {
            $question = EEM_Question::instance()->get_one_by_ID($question_id);
            $answer = $command->csvColumnValue($csv_column);
            if (EEM_Question::instance()->question_type_is_in_category($question->type(), 'multi-answer-enum')) {
                $answer = explode(',', $answer);
            }
            $answer = EE_Answer::new_instance(
                [
                    'REG_ID' => $reg->ID(),
                    'QST_ID' => $question_id,
                    'ANS_value' => $answer
                ]
            );
            $answer->save();
        }
        return null;
    }
}
