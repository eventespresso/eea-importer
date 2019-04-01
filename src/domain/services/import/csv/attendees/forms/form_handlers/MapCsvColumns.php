<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers;

use DomainException;
use EE_Error;
use EE_Form_Section_Proper;
use EE_Registry;
use EED_Attendee_Importer;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms\MapCsvColumnsForm;
use EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\core\services\options\JsonWpOptionManager;
use InvalidArgumentException;
use LogicException;

/**
 * Class MapCsvColumns
 *
 * Step for uploading the CSV file to import.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class MapCsvColumns extends ImportCsvAttendeesStep
{

    /**
     * MapCsvColumns constructor
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
            2,
            esc_html__('Map CSV Columns To Event Espresso Data', 'event_espresso'),
            esc_html__('"Map CSV Columns to Event Espresso Data" Attendee Importer Step', 'event_espresso'),
            'map',
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
     */
    public function generate()
    {
        $this->option_manager->populateFromDb($this->config);
        return new MapCsvColumnsForm(
            array(),
            $this->config
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
        } catch (InvalidFormSubmissionException  $e) {
            return false;
        }
        $this->config->clearMapping();
        $model_configs = $this->config->getModelConfigs();
        $question_mapping = [];
        foreach ($valid_data['columns'] as $column_name => $model_and_field) {
            $model_and_field_array = explode('.', $model_and_field, 2);
            if ($model_and_field_array[0] === 'Question') {
                $question_mapping[ $model_and_field_array[1] ] = $column_name;
            }
            $model_config = $model_configs->get($model_and_field_array[0]);
            if ($model_config instanceof ImportModelConfigInterface) {
                $model_config->map($column_name, $model_and_field_array[1]);
            }
        }
        $this->config->setQuestionMapping($question_mapping);
        $this->option_manager->saveToDb($this->config);
        $this->setRedirectTo(SequentialStepForm::REDIRECT_TO_NEXT_STEP);
        return true;
    }
}
// End of file MapCsvColumns.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers/MapCsvColumns.php
