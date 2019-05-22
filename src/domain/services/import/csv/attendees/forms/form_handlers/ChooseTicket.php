<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers;

use DomainException;
use EE_Admin_Page;
use EE_Error;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Registry;
use EE_Select_Ajax_Model_Rest_Input;
use EED_Attendee_Importer;
use EEH_HTML;
use EEH_URL;
use EEM_Ticket;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\AttendeeImporter\domain\services\import\managers\ui\ImportCsvAttendeesUiManager;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use EventEspresso\core\services\options\JsonWpOptionManager;
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
class ChooseTicket extends ImportCsvAttendeesStep
{
    /**
     * @var EEM_Ticket
     */
    private $ticket_model;

    /**
     * ChooseTicket constructor
     *
     * @param EE_Registry $registry
     * @param ImportCsvAttendeesConfig $config
     * @param JsonWpOptionManager $option_manager
     * @param ImportCsvAttendeesUiManager $attendeesUiManager
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     */
    public function __construct(
        EE_Registry $registry,
        ImportCsvAttendeesConfig $config,
        JsonWpOptionManager $option_manager,
        EEM_Ticket $ticket_model
    ) {
        $this->setDisplayable(true);
        $this->has_help_tab = true;
        parent::__construct(
            2,
            esc_html__('Choose Ticket', 'event_espresso'),
            esc_html__('"Choose Ticket" Attendee Importer Step', 'event_espresso'),
            'choose-ticket',
            '',
            FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
            $registry,
            $config,
            $option_manager
        );
        $this->ticket_model = $ticket_model;
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
        $this->option_manager->populateFromDb($this->config);
        $last_selected_ticket_id = $this->config->getTicketId();
        // Ok we remember what they chose last time. But let's verify they were importing to the same event,
        // because if they're now importing to a different event, we obviously don't want to reuse the ticket!
        if($last_selected_ticket_id &&
            ! $this->ticket_model->exists(
                [
                    [
                        'TKT_ID' => $last_selected_ticket_id,
                        'Datetime.EVT_ID' => $this->config->getEventId()
                    ]
                ]
            )){
            $last_selected_ticket_id = null;
        }
        return new EE_Form_Section_Proper(
            [
                'name' => 'ticket',
                'subsections' => [
                    'header' => new EE_Form_Section_HTML(
                        EEH_HTML::h2(
                            esc_html__('Select Ticket', 'event_espresso')
                            . $this->getHelpTabLink()
                        )
                    ),
                    'ticket' => new EE_Select_Ajax_Model_Rest_Input(
                        [
                            'model_name' => 'Ticket',
                            'required' => true,
                            'html_label_text' => esc_html__('Ticket', 'event_espresso'),
                            'html_help_text' => esc_html__('The Ticket data should be imported to.', 'event_espresso'),
                            'query_params' => [
                                [
                                    'Datetime.Event.EVT_ID' => $this->config->getEventId()
                                ]
                            ],
                            'default' => $last_selected_ticket_id
                        ]
                    ),
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

        $this->config->setTicketId($valid_data['ticket']);
        $this->option_manager->saveToDb($this->config);
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file ChooseTicket.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers/ChooseTicket.php
