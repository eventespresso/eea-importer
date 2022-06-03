<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Transaction;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\commands\CommandInterface;
use EventEspresso\core\services\commands\CompositeCommandHandler;
use InvalidArgumentException;

/**
 * Class CreateAttendeeCommandHandler
 * Creates a transaction and line item tree from the inputted data.
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 */
class ImportTransactionCommandHandler extends CompositeCommandHandler
{
    /**
     * @param ImportTransactionCommand $command
     * @return EE_Transaction
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     * @var EE_Transaction             $transaction
     */
    public function handle(CommandInterface $command): EE_Transaction
    {
        $this->verify($command);
        // Create a transaction
        $transaction = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\transaction\CreateTransactionCommand',
                [
                    null,
                    [],
                ]
            )
        );
        // Mark the transaction as complete eh.
        $transaction->set_reg_steps(
            [
                'attendee_information' => true,
                'payment_options' => true,
                'finalize_registration' => current_time('timestamp'),
            ]
        );
        // Save the transaction to the DB.
        $transaction->save();
        return $transaction;
    }
}
