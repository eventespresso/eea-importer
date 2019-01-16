<?php

namespace EventEspresso\AttendeeImporter\form;
use DomainException;
use EE_Form_Section_Proper;
use EE_Registry;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;

/**
 * Class UploadCsv
 *
 * Step for uploading the CSV file to import.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class UploadCsv extends SequentialStepForm
{

    /**
     * UploadCsv constructor
     *
     * @param EE_Registry $registry
     * @throws InvalidDataTypeException
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function __construct(EE_Registry $registry)
    {
        $this->setDisplayable(true);
        parent::__construct(
            1,
            esc_html__('Upload', 'event_espresso'),
            esc_html__('"Upload" Attendee Importer Step', 'event_espresso'),
            'upload',
            '',
            FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
            $registry
        );
    }


    /**
     * creates and returns the actual form
     *
     * @return EE_Form_Section_Proper
     */
    public function generate()
    {
        return new EE_Form_Section_Proper(
            [
                'name' => 'upload'
            ]
        );
    }
}
// End of file UploadCsv.php
// Location: EventEspresso\AttendeeImporter\form/UploadCsv.php
