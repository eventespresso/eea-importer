<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers;

use DomainException;
use EE_Error;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Registration;
use EE_Registry;
use EventEspresso\core\exceptions\EntityNotFoundException;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\UnexpectedEntityException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;
use LogicException;
use OutOfRangeException;
use ReflectionException;
use RuntimeException;
use EEH_HTML;

/**
 * Class Complete
 * final form in the sequential form steps for the Attendee Importer admin page
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 * @since         1.0.0
 */
class Complete extends SequentialStepForm
{


    /**
     * SelectTicket constructor
     *
     * @param EE_Registry $registry
     * @throws InvalidDataTypeException
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws EE_Error
     * @throws ReflectionException
     */
    public function __construct(EE_Registry $registry)
    {
        parent::__construct(
            5,
            esc_html__('Complete', 'event_espresso'),
            esc_html__('"Complete" Attendee Mover Step', 'event_espresso'),
            'complete',
            '',
            FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
            $registry
        );
    }


    /**
     * creates and returns the actual form
     *
     * @throws EntityNotFoundException
     * @throws LogicException
     * @throws EE_Error
     */
    public function generate()
    {
        $this->setForm(
            new EE_Form_Section_Proper(
                array(
                    'name'        => $this->slug(),
                    'subsections' => [
                        'input1' => new EE_Form_Section_HTML(
                            EEH_HTML::h2(esc_html__('Import Successful', 'event_espresso'))
                            . 'more content to be placed here...'
                        )
                    ]
                )
            )
        );
        return $this->form();
    }


    /**
     * handles processing the form submission
     * returns true or false depending on whether the form was processed successfully or not
     *
     * @param array $form_data
     * @return bool
     * @throws InvalidEntityException
     * @throws ReflectionException
     * @throws InvalidDataTypeException
     * @throws OutOfRangeException
     * @throws RuntimeException
     * @throws UnexpectedEntityException
     * @throws LogicException
     * @throws InvalidFormSubmissionException
     * @throws EE_Error
     * @throws EntityNotFoundException
     * @throws InvalidArgumentException
     */
    public function process($form_data = array())
    {
        // setup redirect to new registration details admin page
        $this->setRedirectUrl(REG_ADMIN_URL);
        // and update the redirectTo constant as well
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_OTHER);
        return true;
    }
}
