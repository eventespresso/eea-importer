<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\forms;

use EE_Error;
use EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use PHPUnit_Framework_TestCase;

/**
 * Class MapCsvColumnsFormTest
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
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
     * @since $VID:$
     * @return MapCsvColumnsForm
     * @throws EE_Error
     */
    protected function generateForm()
    {
        $config = new ImportCsvAttendeesConfig();
        $config->setFile(EE_ATTENDEE_IMPORTER_TEST_CSVS_DIR . 'test.csv');
        $form = new MapCsvColumnsSubform(
            [
                'name' => 'form',
            ],
            $config
        );
        return $form;
    }
}
// End of file MapCsvColumnsFormTest.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\forms/MapCsvColumnsFormTest.php
