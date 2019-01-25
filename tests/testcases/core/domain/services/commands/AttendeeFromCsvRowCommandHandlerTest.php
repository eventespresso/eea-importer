<?php

use EventEspresso\AttendeeImporter\core\domain\services\commands\AttendeeFromCsvRowBaseCommand;
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
            new AttendeeFromCsvRowBaseCommand(
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
        $handler = $this->getCommandHandler();
        $original_attendee_count = EEM_Attendee::instance()->count();
        $attendee = $handler->handle(
            new AttendeeFromCsvRowBaseCommand(
                [
                    'fname column' => 'val1',
                    'lname column' => 'val2',
                    'email column' => 'va@l3.com'
                ]
            )
        );
        $this->assertInstanceOf('EE_Attendee', $attendee);
        $this->assertEquals($original_attendee_count + 1, EEM_Attendee::instance()->count());
        $this->assertEquals('val1', $attendee->fname());
        $this->assertEquals('val2', $attendee->lname());
        $this->assertEquals('va@l3.com', $attendee->email());
    }

    public function testHandleAlreadyExists() {
        $handler = $this->getCommandHandler();
        $original_attendee = $this->new_model_obj_with_dependencies(
            'Attendee',
            [
                'ATT_fname' => 'val1',
                'ATT_lname' => 'val2',
                'ATT_email' => 'va@l3.com'
            ]
        );
        $original_attendee_count = EEM_Attendee::instance()->count();
        $attendee = $handler->handle(
            new AttendeeFromCsvRowBaseCommand(
                [
                    'fname column' => 'val1',
                    'lname column' => 'val2',
                    'email column' => 'va@l3.com'
                ]
            )
        );
        $this->assertInstanceOf('EE_Attendee', $attendee);
        $this->assertEEModelObjectsEquals($original_attendee, $attendee);
        $this->assertEquals($original_attendee_count, EEM_Attendee::instance()->count());
    }

    public function testHandleUpdate() {
        $config = new EE_Attendee_Importer_Config();
        $config->column_mapping = [
            'fname column' => 'Attendee.ATT_fname',
            'lname column' => 'Attendee.ATT_lname',
            'email column' => 'Attendee.ATT_email',
            'address column' => 'Attendee.ATT_address'
        ];
        $handler = new AttendeeFromCsvRowCommandHandler($config);
        $original_attendee = $this->new_model_obj_with_dependencies(
            'Attendee',
            [
                'ATT_fname' => 'val1',
                'ATT_lname' => 'val2',
                'ATT_email' => 'va@l3.com'
            ]
        );
        $original_attendee_count = EEM_Attendee::instance()->count();
        $attendee = $handler->handle(
            new AttendeeFromCsvRowBaseCommand(
                [
                    'fname column' => 'val1',
                    'lname column' => 'val2',
                    'email column' => 'va@l3.com',
                    'address column' => 'val4'
                ]
            )
        );
        $this->assertInstanceOf('EE_Attendee', $attendee);
        $this->assertEEModelObjectsEquals($original_attendee, $attendee);
        $this->assertEquals($original_attendee_count, EEM_Attendee::instance()->count());
        $this->assertEquals('val4', $attendee->address());
    }
}
// End of file AttendeeFromCsvRowCommandHandlerTest.php
// Location: ${NAMESPACE}/AttendeeFromCsvRowCommandHandlerTest.php
