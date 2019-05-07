<?php if (! defined('EVENT_ESPRESSO_VERSION')) {
    exit('No direct script access allowed');
}
/**
*
* Attendee_Importer_Admin_Page_Init class
*
* This is the init for the Attendee_Importer Addon Admin Pages.  See EE_Admin_Page_Init for method inline docs.
*
* @package          Event Espresso (attendee_importer addon)
* @subpackage       admin/Attendee_Importer_Admin_Page_Init.core.php
* @author               Michael Nelson
*
* ------------------------------------------------------------------------
*/
class Attendee_Importer_Admin_Page_Init extends EE_Admin_Page_Init
{

    /**
     *  constructor
     *
     * @access public
     * @return \Attendee_Importer_Admin_Page_Init
     */
    public function __construct()
    {

        do_action('AHEE_log', __FILE__, __FUNCTION__, '');

        define('ATTENDEE_IMPORTER_PG_SLUG', 'espresso_attendee_importer');
        define('ATTENDEE_IMPORTER_LABEL', __('Attendee Importer', 'event_espresso'));
        define('EE_ATTENDEE_IMPORTER_ADMIN_URL', admin_url('admin.php?page=' . ATTENDEE_IMPORTER_PG_SLUG));
        define('EE_ATTENDEE_IMPORTER_ADMIN_ASSETS_PATH', EE_ATTENDEE_IMPORTER_ADMIN . 'assets' . DS);
        define('EE_ATTENDEE_IMPORTER_ADMIN_ASSETS_URL', EE_ATTENDEE_IMPORTER_URL . 'admin' . DS . 'attendee_importer' . DS . 'assets' . DS);
        define('EE_ATTENDEE_IMPORTER_ADMIN_TEMPLATE_PATH', EE_ATTENDEE_IMPORTER_ADMIN . 'templates' . DS);
        define('EE_ATTENDEE_IMPORTER_ADMIN_TEMPLATE_URL', EE_ATTENDEE_IMPORTER_URL . 'admin' . DS . 'attendee_importer' . DS . 'templates' . DS);

        parent::__construct();
        $this->_folder_path = EE_ATTENDEE_IMPORTER_ADMIN;
    }





    protected function _set_init_properties()
    {
        $this->label = ATTENDEE_IMPORTER_LABEL;
    }



    /**
    *       _set_menu_map
    *
    *       @access         protected
    *       @return         void
    */
    protected function _set_menu_map()
    {
        $this->_menu_map = new EE_Admin_Page_Sub_Menu(array(
            'menu_group' => 'addons',
            'menu_order' => 25,
            'show_on_menu' => EE_Admin_Page_Menu_Map::BLOG_ADMIN_ONLY,
            'parent_slug' => 'espresso_events',
            'menu_slug' => ATTENDEE_IMPORTER_PG_SLUG,
            'menu_label' => ATTENDEE_IMPORTER_LABEL,
            'capability' => 'import',
            'admin_init_page' => $this
        ));
    }
}
// End of file Attendee_Importer_Admin_Page_Init.core.php
// Location: /wp-content/plugins/eea-attendee-importer/admin/attendee_importer/Attendee_Importer_Admin_Page_Init.core.php
