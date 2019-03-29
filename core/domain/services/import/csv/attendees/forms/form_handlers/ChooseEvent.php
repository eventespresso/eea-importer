<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers;

use DomainException;
use EE_Error;
use EE_Form_Section_Proper;
use EE_Registry;
use EE_Select_Ajax_Model_Rest_Input;
use EED_Attendee_Importer;
use EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use EventEspresso\core\services\options\JsonWpOptionManager;
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
class ChooseEvent extends ImportCsvAttendeesStep
{

    /**
     * ChooseEvent constructor
     *
     * @param EE_Registry $registry
     * @param ImportCsvAttendeesConfig $config
     * @param JsonWpOptionManager $option_manager
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     */
    public function __construct(
        EE_Registry $registry,
        ImportCsvAttendeesConfig $config,
        JsonWpOptionManager $option_manager
    ) {
        $this->setDisplayable(true);
        parent::__construct(
            3,
            esc_html__('Choose Event', 'event_espresso'),
            esc_html__('"Choose Event" Attendee Importer Step', 'event_espresso'),
            'choose-event',
            '',
            FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
            $registry,
            $config,
            $option_manager
        );
    }


    /**
     * creates and returns the actual form
     *
     * @return EE_Form_Section_Proper
     */
    public function generate()
    {
        $this->option_manager->populateFromDb($this->config);
        return new EE_Form_Section_Proper(
            [
                'name' => 'event',
                'subsections' => [
                    'event' => new EE_Select_Ajax_Model_Rest_Input(
                        [
                            'model_name' => 'Event',
                            'required' => true,
                            'help_text' => esc_html__('The Event data should be imported to.', 'event_espresso'),
                            'default' => $this->config->getEventId(),
                            'query_params' => [
                                'order_by' => ['EVT_ID' => 'DESC']
                            ]
                        ]
                    )
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
        try {
            $valid_data = (array) parent::process($form_data);
        } catch (InvalidFormSubmissionException $e) {
            // Don't die. Admin code knows how to handle invalid forms...
            return;
        }
        $this->config->setEventId($valid_data['event']);
        $this->option_manager->saveToDb($this->config);
        // If there is only one ticket for this event, we can set the default ticket now and skip that step.s
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file ChooseEvent.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers/ChooseEvent.php
