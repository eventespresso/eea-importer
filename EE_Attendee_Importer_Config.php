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
     * @return EE_Mailchimp_Config
     */
    public function __construct()
    {
        $this->file = '';
        $this->column_mapping = [];
    }

}



// End of file EE_Attendee_Importer_Config.php
// Location: /wp-content/plugins/eea-attendee-importer/EE_Attendee_Importer_Config.php