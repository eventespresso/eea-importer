<?php

namespace EventEspresso\AttendeeImporter\form;
use DomainException;
use EE_Error;
use EE_Form_Section_Proper;
use EE_Registry;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;
use LogicException;

/**
 * Class ChooseEvent
 *
 * Step for uploading the CSV file to import.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ChooseEvent extends SequentialStepForm
{

    /**
     * ChooseEvent constructor
     *
     * @param EE_Registry $registry
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidDataTypeException
     */
    public function __construct(EE_Registry $registry)
    {
        $this->setDisplayable(true);
        parent::__construct(
            3,
            esc_html__('Choose Event', 'event_espresso'),
            esc_html__('"Choose Event" Attendee Importer Step', 'event_espresso'),
            'choose-event',
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
                'name' => 'event',
                'subsections' => [
                    'input1' => new \EE_Text_Input()
                ]
            ]
        );
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
        $valid_data = (array) parent::process($form_data);
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file ChooseEvent.php
// Location: EventEspresso\AttendeeImporter\form/ChooseEvent.php
