<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms;

use EE_Error;
use EEM_Ticket;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use PHPUnit_Framework_TestCase;
use ReflectionException;

/**
 * Class MapCsvColumnsFormTest
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         1.0.0.p
 *
 */
class MapCsvColumnsSubformTest extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $form = $this->generateForm();
        $this->assertInstanceOf('EE_Form_Input_Base', $form->get_input('Column 1'));
    }

    public function testValidate()
    {
        $form = $this->generateForm();
        $form->receive_form_submission(
            [
                'form' => [
                    'Column 1' => 'Attendee.ATT_fname',
                    'Column 2' => 'Attendee.ATT_lname', // Two columns mapped to the attendee first name!
                    'Column 3' => 'Attendee.ATT_email',
                ]
            ]
        );
        $this->assertTrue($form->is_valid());
    }

    public function testValidateTwoAddresses()
    {
        $form = $this->generateForm();
        $form->receive_form_submission(
            [
                'form' => [
                    'Column 1' => 'Attendee.ATT_fname',
                    'Column 2' => 'Attendee.ATT_fname', // Two columns mapped to the attendee first name!
                    'Column 3' => 'Attendee.ATT_lname',
                ]
            ]
        );
        $this->assertFalse($form->is_valid());
    }

    public function testValidateMissingFirstname()
    {
        $form = $this->generateForm();
        $form->receive_form_submission(
            [
                'form' => [
                    'Column 1' => '',
                    'Column 2' => 'Attendee.ATT_lname',
                    'Column 3' => 'Attendee.ATT_email',
                ]
            ]
        );
        $this->assertFalse($form->is_valid());
    }

    public function testValidateMissingEmail()
    {
        $form = $this->generateForm();
        $form->receive_form_submission(
            [
                'form' => [
                    'Column 1' => 'Attendee.ATT_fname',
                    'Column 2' => 'Attendee.ATT_lname',
                    'Column 3' => '',
                ]
            ]
        );
        $this->assertFalse($form->is_valid());
    }


    /**
     * @return MapCsvColumnsSubform
     * @throws EE_Error
     *@throws ReflectionException
     * @since 1.0.0.p
     */
    protected function generateForm(): MapCsvColumnsSubform
    {
        $config = new ImportCsvAttendeesConfig(EEM_Ticket::instance());
        $config->setFile(EE_ATTENDEE_IMPORTER_TEST_CSVS_DIR . 'test.csv');
        return new MapCsvColumnsSubform($config, ['name' => 'form']);
    }
}
// End of file MapCsvColumnsFormTest.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms/MapCsvColumnsFormTest.php
