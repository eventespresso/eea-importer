<?php
namespace EventEspresso\AttendeeImporter\form;

use EventEspresso\core\exceptions\InvalidClassException;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidFilePathException;
use EventEspresso\core\exceptions\InvalidIdentifierException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepFormManager;
use EventEspresso\core\services\collections\Collection;
use EventEspresso\core\services\collections\CollectionDetails;
use EventEspresso\core\services\collections\CollectionLoader;

/**
 * Class StesManager
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class StepsManager extends SequentialStepFormManager
{

    /**
     * StepsManager constructor
     *
     * @param string      $base_url
     * @param string      $default_form_step
     * @param string      $form_action
     * @param string      $form_config
     * @param string      $progress_step_style
     * @param \EE_Request $request
     * @throws InvalidDataTypeException
     * @throws InvalidArgumentException
     */
    public function __construct(
        $base_url,
        $default_form_step,
        $form_action = '',
        $form_config = FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
        $progress_step_style = 'number_bubbles',
        \EE_Request $request = null
    ) {
        parent::__construct(
            $base_url,
            $default_form_step,
            $form_action,
            $form_config,
            $progress_step_style,
            $request
        );
    }


    /**
     * @return Collection|null
     * @throws \EventEspresso\core\services\collections\CollectionDetailsException
     * @throws \EventEspresso\core\services\collections\CollectionLoaderException
     */
    protected function getFormStepsCollection()
    {
        if (! $this->form_steps instanceof Collection) {
            $loader = new CollectionLoader(
                new CollectionDetails(
                    // collection name
                    'attendee_importer_form_steps',
                    // collection interface
                    'EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm',
                    // FQCNs for classes to add
                    apply_filters(
                        // @codingStandardsIgnoreStart
                        'FHEE__EventEspresso\AttendeeImporter\form\StepsManager__getFormStepsCollection__form_step_classes',
                        // @codingStandardsIgnoreEnd
                        array(
                            'EventEspresso\AttendeeImporter\form\UploadCsv',
                            'EventEspresso\AttendeeImporter\form\MapCsvColumns',
                            'EventEspresso\AttendeeImporter\form\ChooseEvent',
                            'EventEspresso\AttendeeImporter\form\Import',
                            'EventEspresso\AttendeeImporter\form\Complete',
                        )
                    ),
                    // filepaths to classes to add
                    array(),
                    // filemask to use if parsing folder for files to add
                    '',
                    // what to use as identifier for collection entities
                    CollectionDetails::ID_CALLBACK_METHOD,
                    // we'll use the slug() method on our collection objects for setting the identifier
                    'slug'
                )
            );
            $this->form_steps = $loader->getCollection();
        }
        return $this->form_steps;
    }

}
// End of file StesManager.php
// Location: EventEspresso\AttendeeImporter\form/StesManager.php
