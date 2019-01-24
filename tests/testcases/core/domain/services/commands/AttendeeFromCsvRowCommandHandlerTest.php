<?php

use EventEspresso\AttendeeImporter\core\domain\services\commands\AttendeeFromCsvRowCommand;
use EventEspresso\AttendeeImporter\core\domain\services\commands\AttendeeFromCsvRowCommandHandler;

/**
 * Class AttendeeFromCsvRowCommandHandlerTest
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 * @group test
 */
class AttendeeFromCsvRowCommandHandlerTest extends EE_UnitTestCase
{
    public function getCommandHandler()
    {
        $config = new EE_Attendee_Importer_Config();
        $config->column_mapping = [
            'fname column' => 'Attendee.ATT_fname',
            'lname column' => 'Attendee.ATT_lname',
            'email column' => 'Attendee.ATT_email'
        ];
        return new AttendeeFromCsvRowCommandHandler(
            $config
        );
    }

    /**
     * @since $VID:$
     * @expectedException DomainException
     */
    public function testHandleInsufficientDetails() {
        $config = new EE_Attendee_Importer_Config();
        $config->column_mapping = [
            // note: we haven't specified a column for email. That will cause this to fail.
            'fname column' => 'Attendee.ATT_fname',
        ];
        $handler = new AttendeeFromCsvRowCommandHandler(
            $config
        );

        $handler->handle(
            new AttendeeFromCsvRowCommand(
                [
                    'fname column' => 'val1',
                    'lname column' => 'val2',
                    'email column' => 'val3'
                ]
            )
        );
    }

//    public function testHandleInsufficientDetails() {
//        $config = new EE_Attendee_Importer_Config();
//        $config->column_mapping = [
//            'fname column' => 'Attendee.ATT_fname',
//            'lname column' => 'Attendee.ATT_lname',
//            'email column' => 'Attendee.ATT_email'
//        ];
//        $handler = new AttendeeFromCsvRowCommandHandler(
//            $config
//        );
//
//        $handler->handle(
//            new AttendeeFromCsvRowCommand(
//                [
//                    'fname column' => 'val1',
//                    'lname column' => 'val2',
//                    'email column' => 'val3'
//                ]
//            )
//        );
//    }

    public function testHandleCreate() {

    }

    public function testHandleAlreadyExists() {

    }

    public function testHandleUpdate() {

    }
}
// End of file AttendeeFromCsvRowCommandHandlerTest.php
// Location: ${NAMESPACE}/AttendeeFromCsvRowCommandHandlerTest.php
