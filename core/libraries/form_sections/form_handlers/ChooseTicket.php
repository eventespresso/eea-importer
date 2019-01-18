<?php

namespace EventEspresso\AttendeeImporter\core\libraries\form_sections\form_handlers;

use DomainException;
use EE_Attendee_Importer_Config;
use EE_Error;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Registry;
use EE_Select_Ajax_Model_Rest_Input;
use EED_Attendee_Importer;
use EEH_HTML;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;
use LogicException;

/**
 * Class ChooseTicket
 *
 * Step for selecting which ticket will be imported to.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ChooseTicket extends SequentialStepForm
{

    /**
     * @var EE_Attendee_Importer_Config
     */
    protected $config;

    /**
     * ChooseTicket constructor
     *
     * @param EE_Registry $registry
     * @param EE_Attendee_Importer_Config $config
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidDataTypeException
     */
    public function __construct(EE_Registry $registry, EE_Attendee_Importer_Config $config)
    {
        $this->config = $config;
        $this->setDisplayable(true);
        parent::__construct(
            4,
            esc_html__('Choose Ticket', 'event_espresso'),
            esc_html__('"Choose Ticket" Attendee Importer Step', 'event_espresso'),
            'choose-ticket',
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
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    public function generate()
    {
        return new EE_Form_Section_Proper(
            [
                'name' => 'ticket',
                'subsections' => [
                    'ticket' => new EE_Select_Ajax_Model_Rest_Input(
                        [
                            'model_name' => 'Ticket',
                            'required' => true,
                            'help_text' => esc_html__('The Ticket data should be imported to.', 'event_espresso'),
                            'query_params' => [
                                [
                                    'Datetime.Event.EVT_ID' => $this->config->default_event
                                ]
                            ]
                        ]
                    ),
                    'notice' => new EE_Form_Section_HTML(
                        EEH_HTML::p(esc_html__('The import will start after this step. Please wait for it to complete before closing this window, turning off your computer, or navigating away.', 'event_espresso'))
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
        try{
            $valid_data = (array) parent::process($form_data);
        }catch(InvalidFormSubmissionException $e){
            // Don't die. Admin code knows how to handle invalid forms...
            return;
        }
        $config = EED_Attendee_Importer::instance()->getConfig();
        $config->default_ticket = $valid_data['ticket'];
        EED_Attendee_Importer::instance()->updateConfig();
        // If there is only one ticket for this event, we can set the default ticket now and skip that step.
        
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file ChooseTicket.php
// Location: EventEspresso\AttendeeImporter\core\libraries\form_sections\form_handlers/ChooseTicket.php
