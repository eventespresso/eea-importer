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
     * @var ImportCsvAttendeesUiManager
     */
    private $attendeesUiManager;

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
        ImportCsvAttendeesUiManager $attendeesUiManager
    ) {
        $this->setDisplayable(true);
        $this->attendeesUiManager = $attendeesUiManager;
        parent::__construct(
            4,
            esc_html__('Choose Ticket', 'event_espresso'),
            esc_html__('"Choose Ticket" Attendee Importer Step', 'event_espresso'),
            'choose-ticket',
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
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    public function generate()
    {
        $this->option_manager->populateFromDb($this->config);
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
                                    'Datetime.Event.EVT_ID' => $this->config->getEventId()
                                ]
                            ],
                            'default' => $this->config->getTicketId()
                        ]
                    ),
                    'notice' => new EE_Form_Section_HTML(
                        EEH_HTML::p(
                            esc_html__(
                                // @codingStandardsIgnoreStart
                                'The import will start after this step. Please wait for it to complete before closing this window, turning off your computer, or navigating away.',
                                // @codingStandardsIgnoreEnd
                                'event_espresso'
                            )
                        )
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
        $this->redirectToBatchJob();
        return true;
    }

    protected function redirectToBatchJob()
    {
        wp_redirect(
            EE_Admin_Page::add_query_args_and_nonce(
                array(
                    'page'        => 'espresso_batch',
                    'batch'       => 'job',
                    'label'       => esc_html__('Applying Offset', 'event_espresso'),
                    'job_handler' => urlencode(get_class($this->attendeesUiManager->getBatchJobHandler())),
                    'return_url'  => urlencode(
                        add_query_arg(
                            array(
                                'ee-form-step' => 'complete',
                            ),
                            EEH_URL::current_url_without_query_paramaters(
                                array(
                                    'ee-form-step',
                                    'return',
                                )
                            )
                        )
                    ),
                ),
                admin_url()
            )
        );
        exit;
    }
}
// End of file ChooseTicket.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers/ChooseTicket.php
