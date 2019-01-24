<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * Event Espresso
 *
 * Event Registration and Ticketing Management Plugin for WordPress
 *
 * HOw to save config, in case we want to go that way again.
 * $config = EE_Config::instance()->get_config(
        'addons',
        'Attendee_Importer',
        'EE_Attendee_Importer_Config'
    );
    if( ! $config instanceof EE_Attendee_Importer_Config) {
        $config = new EE_Attendee_Importer_Config();
    }
    $config->file = $valid_data['file'];
    EE_Config::instance()->set_config('addons', 'Attendee_Importer', 'EE_Attendee_Importer_Config', $config);
    EE_Config::instance()->update_config('addons', 'Attendee_Importer', $config);
 *
 * @ package			Event Espresso
 * @ author			    Event Espresso
 * @ copyright		(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 *
 *
 * ------------------------------------------------------------------------
 */
 /**
 *
 * Class EE_Attendee_Importer_Config
 *
 * Description
 *
 * @package         Event Espresso
 * @subpackage    core
 * @author				Brent Christensen
 * 
 *
 */

class EE_Attendee_Importer_Config extends EE_Config_Base {

    /**
     * @var string filepath to file currently being imported
     */
    public $file;

    /**
     * @var array
     */
    public $column_mapping;

    /**
     * @var int
     */
    public $default_event;

    /**
     * @var int
     */
    public $default_ticket;


    /**
     * @return EE_Mailchimp_Config
     */
    public function __construct()
    {
        $this->file = '';
        $this->column_mapping = [];
        $this->default_event = 0;
        $this->default_ticket = 0;
    }

    /**
     * Gets all the CSV columns that correspond to this model. The array keys are the field names (or object IDs)
     * @since $VID:$
     * @param EEM_Base $model
     * @return array keys are the model's field names or object IDs; values are the CSV columns that correspond to them.
     */
    public function getCsvColumnsForModel(EEM_Base $model)
    {
        $fields_to_columns = [];
        $model_name_start = $model->get_this_model_name() . '.';
        foreach ($this->column_mapping as $column => $model_period_field) {
            if (strpos($model_period_field, $model_name_start) === 0) {
                $fields_to_columns[str_replace($model_name_start,'',$model_period_field)] = $column;
            }
        }
        return $fields_to_columns;
    }

}



// End of file EE_Attendee_Importer_Config.php
// Location: /wp-content/plugins/eea-attendee-importer/EE_Attendee_Importer_Config.php