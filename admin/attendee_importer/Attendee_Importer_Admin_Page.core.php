<?php use EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers\StepsManager;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\libraries\form_sections\form_handlers\InvalidFormHandlerException;
use EventEspresso\core\services\loaders\LoaderFactory;

if (!defined('EVENT_ESPRESSO_VERSION')) {
    exit('NO direct script access allowed');
}

/**
 *
 * Attendee_Importer_Admin_Page
 *
 * This contains the logic for setting up the Attendee_Importer Addon Admin related pages.  Any methods without PHP doc comments have inline docs with parent class.
 *
 *
 * @package            Attendee_Importer_Admin_Page (attendee_importer addon)
 * @subpackage    admin/Attendee_Importer_Admin_Page.core.php
 * @author                Darren Ethier, Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class Attendee_Importer_Admin_Page extends EE_Admin_Page
{


    protected function _init_page_props()
    {
        $this->page_slug = ATTENDEE_IMPORTER_PG_SLUG;
        $this->page_label = ATTENDEE_IMPORTER_LABEL;
        $this->_admin_base_url = EE_ATTENDEE_IMPORTER_ADMIN_URL;
        $this->_admin_base_path = EE_ATTENDEE_IMPORTER_ADMIN;
    }


    protected function _ajax_hooks()
    {
    }


    protected function _define_page_props()
    {
        $this->_admin_page_title = ATTENDEE_IMPORTER_LABEL;
        $this->_labels = array();
    }


    protected function _set_page_routes()
    {
        $this->_page_routes = array(
            'default' => array(
                'func' => 'main',
            ),
            'import' => array(
                'func' => 'import',
                'noheader' => true,
                'headers_sent_route' => 'show_import_step'
            ),
            'usage' => 'usage',
            'show_import_step' => array(
                'func' => 'show_import_step'
            )
        );
    }


    protected function _set_page_config()
    {

        $this->_page_config = array(
            'default' => array(
                'require_nonce' => false
            ),
            'usage' => array(
                'nav' => array(
                    'label' => __('Attendee Importer Usage', 'event_espresso'),
                    'order' => 30
                ),
                'require_nonce' => false
            ),
            'show_import_step' => array(
                'nav' => array(
                    'label' => __('Import', 'event_espresso'),
                    'order' => 10
                ),
                'require_nonce' => false
            )
        );
    }


    protected function _add_screen_options()
    {
    }

    protected function _add_screen_options_default()
    {
    }

    protected function _add_feature_pointers()
    {
    }

    public function load_scripts_styles()
    {
        wp_register_script('espresso_attendee_importer_admin', EE_ATTENDEE_IMPORTER_ADMIN_ASSETS_URL . 'espresso_attendee_importer_admin.js', array('espresso_core'), EE_ATTENDEE_IMPORTER_VERSION, TRUE);
        wp_enqueue_script('espresso_attendee_importer_admin');
    }

    public function admin_init()
    {
    }

    public function admin_notices()
    {
    }

    public function admin_footer_scripts()
    {
    }


    protected function usage()
    {
        $this->_template_args['admin_page_content'] = EEH_Template::display_template(EE_ATTENDEE_IMPORTER_ADMIN_TEMPLATE_PATH . 'attendee_importer_usage_info.template.php', array(), TRUE);
        $this->display_admin_page_with_no_sidebar();
    }

    /**
     * Shows the list of importers available.
     * @since $VID:$
     */
    protected function main()
    {
        $import_manager = $this->getImportManager();
        /* @var $import_manager EventEspresso\core\services\import\ImportManager */
        $import_type_ui_managaers = $import_manager->loadImportTypeUiManagers();
        $html = '';
        foreach($import_type_ui_managaers as $ui_manager) {
            $import_type = $ui_manager->getImportType();
            $html .= EEH_Template::display_template(
                EE_ATTENDEE_IMPORTER_ADMIN_TEMPLATE_PATH . 'attendee_importer_manager_type.template.php',
                [
                    'slug' => $import_type->getSlug(),
                    'name' => $import_type->getName(),
                    'description' => $import_type->getDescription(),
                    'image_url' => $ui_manager->getImage(),
                    'steps_url' => add_query_arg(
                        [
                            'action' => 'show_import_step',
                            'type' => $import_type->getSlug()
                        ],
                        EE_ATTENDEE_IMPORTER_ADMIN_URL
                    )
                ]
            );
        }
        $this->_template_args['admin_page_content'] = $html;
    }

    /**
     * @since $VID:$
     * @return EventEspresso\AttendeeImporter\core\services\import\ImportManager
     * @throws InvalidArgumentException
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     */
    protected function getImportManager()
    {
        return LoaderFactory::getLoader()->load('EventEspresso\AttendeeImporter\core\services\import\ImportManager');
    }

    /**
     * @since $VID:$
     * @throws InvalidArgumentException
     * @throws InvalidFormHandlerException
     */
    protected function import()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $form_steps_manager = $this->getFormStepManager();
                $form_steps_manager->processForm($_POST);
            } catch (Exception $e) {
                new ExceptionStackTraceDisplay($e);
            }
        }
    }

    protected function show_import_step()
    {
        try {
            $form_steps_manager = $this->getFormStepManager();
            $this->_template_args['admin_page_content'] = $form_steps_manager->displayProgressSteps()
                . $form_steps_manager->displayCurrentStepForm();
        } catch (Exception $e) {
            new ExceptionStackTraceDisplay($e);
        }

        $this->display_admin_page_with_sidebar();
    }

    /**
     * @param bool $process
     * @return EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepFormManager
     * @throws InvalidArgumentException
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \EventEspresso\core\services\collections\CollectionLoaderException
     */
    public function getFormStepManager()
    {
        return $this->getImportManager()
            ->getUiManager($this->_req_data['type'])
            ->getStepManager(EE_Admin_Page::add_query_args_and_nonce(
                ['action' => 'import',
                    'type' => $this->_req_data['type']
                ],
                EE_ATTENDEE_IMPORTER_ADMIN_URL
            )
        );
    }


}
// End of file Attendee_Importer_Admin_Page.core.php
// Location: /wp-content/plugins/eea-attendee-importer/admin/attendee_importer/Attendee_Importer_Admin_Page.core.php