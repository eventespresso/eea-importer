<?php
namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\forms;
use EE_Error;
use EE_Form_Section_HTML_From_Template;
use EE_Form_Section_Proper;
use EE_Model_Field_Base;
use EE_Select_Input;
use EEM_Answer;
use EEM_Attendee;
use EEM_Base;
use EEM_Payment;
use EEM_Registration;
use EEM_Transaction;
use EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\AttendeeImporter\core\services\import\mapping\ImportFieldMap;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class ColumnMappingForm
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class MapCsvColumnsForm extends EE_Form_Section_Proper
{
    /**
     * @var ImportCsvAttendeesConfig
     */
    protected $config;
    public function __construct($options_array = array(), ImportCsvAttendeesConfig $config)
    {
        $this->config = $config;
        $column_headers = $this->getColumnHeadersFromFile();
        $columns_inputs = [];
        $options = $this->initMapToOptions();
        foreach ($column_headers as $column_header) {
            $columns_inputs[$column_header] = new EE_Select_Input(
                $options,
                [
                    'default' => $this->getDefaultFor($column_header)
                ]
            );
        }
        $options_array = array_replace_recursive(
            [
                'subsections' => [
                    'instructions' => new EE_Form_Section_HTML_From_Template(
                        wp_normalize_path(dirname(dirname(dirname(__FILE__))) . '/templates/ee_attendee_importer_mapping_instructions.template.php')
                    ),
                    'columns' => new EE_Form_Section_Proper(
                        [
                            'subsections' => $columns_inputs
                        ]
                    ),

                ],
                'html_style' => 'display:flex'
            ],
            $options_array
        );
        parent::__construct($options_array);
    }

    /**
     * Reads the current CSV file and finds its header columns
     * @since $VID:$
     */
    protected function getColumnHeadersFromFile()
    {
        $file_obj = $this->config->getFileHandle();
        return $file_obj->fgetcsv();
    }

    /**
     * Gets the options to map columns to (eg attendee firstname, registration code, payment amount, etc).
     * @since $VID:$
     * @return array
     * @throws EE_Error
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     */
    protected function initMapToOptions()
    {
        $options = [
            '' => [
                '' => ''
            ],
        ];
        foreach($this->config->getModelConfigs() as $modelConfig){
            if($modelConfig->getModel() === EEM_Answer::instance()){

            } else {
                $item_name = $modelConfig->getModel()->item_name();
                $options[$item_name] = [];
                foreach($modelConfig->mapping() as $mapped_field) {
                    $input_name = $this->getOptionValueForField($mapped_field->destinationField());
                    $options[$item_name][$input_name] = $mapped_field->destinationField()->get_nicename();
                }
            }
        }
//        // And add questions (group by question group).
//        foreach(\EEM_Question_Group::instance()->get_all() as $question_group){
//            foreach($question_group->questions() as $question) {
//                if( $question->is_system_question()) {
//                    $option_value = 'Attendee.ATT_' . $question->system_ID();
//                } else {
//                    $option_value = 'Question.' . $question->ID();
//                }
//                $options[$question_group->name()][$option_value] = $question->admin_label();
//            }
//        }
//        $options = array_merge(
//            $options,
//            $this->optionsFromModel(
//                EEM_Registration::instance(),
//                [
//                    'STS_ID',
//                    'REG_date',
//                    'REG_final_price',
//                    'REG_paid',
//                    'REG_code',
//                    'REG_count'
//                ]
//            ),
//            $this->optionsFromModel(
//                EEM_Transaction::instance(),
//                [
//                    'STS_ID',
//                    'TXN_total',
//                    'TXN_paid'
//                ]
//            ),
//            $this->optionsFromModel(
//                EEM_Payment::instance(),
//                [
//                    'STS_ID',
//                    'PAY_source',
//                    'PAY_amount',
//                    'PAY_txn_id_chq_nmbr',
//                    'PAY_po_number',
//                    'PAY_extra_accntng'
//                ]
//            )
//        );

        return $options;
    }

    /**
     * Gets the default form value for the CSV column from the config.
     * @since $VID:$
     * @param $column_name
     * @return string
     * @throws EE_Error
     */
    protected function getDefaultFor($column_name)
    {
        foreach ($this->config->getModelConfigs() as $modelConfig) {
            $mapped_info = $modelConfig->getMappingInfoForInput($column_name);
            if ($mapped_info instanceof ImportFieldMap) {
                return $this->getOptionValueForField($mapped_info->destinationField());
            }
        }
    }

    /**
     * Gets the form option value for the field.
     * @since $VID:$
     * @param EE_Model_Field_Base $field
     * @return string
     * @throws EE_Error
     */
    public function getOptionValueForField(EE_Model_Field_Base $field){
        return $field->get_model_name() . '.' . $field->get_name();
    }

//    /**
//     * Generates the input options from the model and its list of fields to include.
//     * @since $VID:$
//     * @param EEM_Base $model
//     * @param $fields_to_include
//     * @return array
//     */
//    protected function optionsFromModel(EEM_Base $model, array $fields_to_include)
//    {
//        $fields = array_intersect_key(
//            $model->field_settings(),
//            array_flip($fields_to_include)
//        );
//        $options = [];
//        foreach($fields as $field){
//            $options[$model->item_name()][$model->get_this_model_name() . '.' . $field->get_name()] = $field->get_nicename();
//        }
//        return $options;
//    }

    /**
     * When validating the form, make sure no two columns have the same value.
     * @since $VID:$
     * @return bool|void
     * @throws EE_Error
     */
    protected function _validate()
    {
        parent::_validate(); // TODO: Change the autogenerated stub
        $valid_data = $this->valid_data();
        foreach ($valid_data as $input_name1 => $value1) {
            foreach ($valid_data as $input_name2 => $value2) {
                if ($input_name1 !== $input_name2
                    && $value1 === $value2
                    && $value1 !== ''
                    && $value1 !== null
                ) {
                    $this->get_input($input_name2)->add_validation_error(
                        sprintf(
                            esc_html__(
                            // translators: %1$s CSV column name, %2$s Event Espresso Data name, %3$s CSV column name
                            // @codingStandardsIgnoreStart
                            'CSV file column "%1$s" maps to the same Event Espresso data (%2$s) as "%3$s". This is not allowed.',
                            // @codingStandardsIgnoreEnd
                            'event_espresso'
                        ),
                            $this->get_input($input_name1)->html_label_text(),
                            $this->get_input($input_name1)->pretty_value(),
                            $this->get_input($input_name2)->html_label_text()
                        )
                    );
                }
            }
        }
    }
}
// End of file ColumnMappingForm.php
// Location: ${NAMESPACE}/ColumnMappingForm.php
