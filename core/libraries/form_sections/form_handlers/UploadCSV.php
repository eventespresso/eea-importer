<?php

namespace EventEspresso\AttendeeImporter\core\libraries\form_sections\form_handlers;
use DomainException;
use EE_Admin_File_Uploader_Input;
use EE_Attendee_Importer_Config;
use EE_Config;
use EE_Error;
use EE_Form_Section_Proper;
use EE_Registry;
use EED_Attendee_Importer;
use EventEspresso\AttendeeImporter\core\libraries\form_sections\forms\UploadCSVForm;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use SplFileObject;
use WP_Query;

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
     * @throws EE_Error
     */
    public function generate()
    {
        return new UploadCSVForm();
    }

    /**
     * handles processing the form submission
     * returns true or false depending on whether the form was processed successfully or not
     *
     * @param array $form_data
     * @return bool
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     * @throws InvalidFormSubmissionException
     * @throws InvalidInterfaceException
     * @throws LogicException
     */
    public function process($form_data = array())
    {
        try {
            $valid_data = (array) parent::process($form_data);
        } catch (InvalidFormSubmissionException $e) {
            return false;
        }
        if (empty($valid_data)) {
            return false;
        }


        $config = EED_Attendee_Importer::instance()->getConfig();

        $config->file = $valid_data['file'];
        EED_Attendee_Importer::instance()->updateConfig();
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file UploadCsv.php
// Location: EventEspresso\AttendeeImporter\core\libraries\form_sections\form_handlers/UploadCsv.php
