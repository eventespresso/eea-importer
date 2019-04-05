<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Attendee;
use EE_Error;
use EE_Payment;
use EE_Payment_Processor;
use EE_Registration;
use EEM_Attendee;
use EEM_Payment;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\services\commands\CommandHandler;
use EventEspresso\core\services\commands\CommandInterface;

/**
 * Class CreateAttendeeCommandHandler
 * generates and validates an Attendee
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 */
class ImportPaymentCommandHandler extends CommandHandler
{
    /**
     * @param CommandInterface|ImportPaymentCommand $command
     * @return EE_Attendee
     * @throws EE_Error
     * @throws InvalidEntityException
     */
    public function handle(CommandInterface $command)
    {
        $payment_data = $command->getFieldsFromMappedData();
        // Don't make a payment if they didn't specify an amount.
        if (empty($payment_data)) {
            return null;
        }
        add_filter(
            'FHEE__EED_Messages___maybe_registration__deliver_notifications',
            '__return_false',
            20
        );
        remove_all_filters('AHEE__EE_Payment_Processor__update_txn_based_on_payment__successful');
        $payment_data['TXN_ID'] = $command->getTransaction()->ID();
        $payment_data['STS_ID'] = EEM_Payment::status_id_approved;
        $payment_data['PAY_timestamp'] = $command->getTransaction()->get_DateTime_object('TXN_timestamp');
        $payment_data['PAY_source'] = esc_html__('Imported', 'event_espresso');
        $payment_data['PMD_ID'] = \EEM_Payment_Method::instance()->get_var(
            [
                [
                    'PMD_slug' => 'other'
                ]
            ],
            'PMD_ID'
        );
        $payment = EE_Payment::new_instance($payment_data);
        $payment->save();
        // No messages while importing thanks.

        EE_Payment_Processor::instance()->update_txn_based_on_payment($command->getTransaction(), $payment);
        return $payment;
    }
}
