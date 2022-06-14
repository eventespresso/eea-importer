<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms;

use EE_Error;
use EE_Form_Section_HTML;
use EE_Form_Section_HTML_From_Template;
use EE_Form_Section_Proper;
use EEH_HTML;
use EventEspresso\core\domain\Domain;
use EventEspresso\core\services\loaders\LoaderFactory;

/**
 * Class ColumnMappingForm
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class MapCsvColumnsForm extends EE_Form_Section_Proper
{
    /**
     * @param array $options_array
     * @throws EE_Error
     */
    public function __construct(array $options_array = [])
    {
        $options_array = array_replace_recursive(
            [
                'subsections' => [
                    'header'       => new EE_Form_Section_HTML(
                        EEH_HTML::h2(
                            sprintf(
                                esc_html__('Map CSV Columns to %s Data', 'event_espresso'),
                                Domain::brandName()
                            )
                            . $options_array['help_tab_link']
                        )
                    ),
                    'instructions' => new EE_Form_Section_HTML_From_Template(
                        wp_normalize_path(
                            dirname(__FILE__, 3) . '/templates/ee_importer_mapping_instructions.template.php'
                        )
                    ),
                    'columns'      => LoaderFactory::getLoader()->getNew(
                        'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms\MapCsvColumnsSubform'
                    ),
                ],
            ],
            $options_array
        );
        parent::__construct($options_array);
    }
}
// End of file ColumnMappingForm.php
// Location: ${NAMESPACE}/ColumnMappingForm.php
