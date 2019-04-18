<?php

use EventEspresso\AttendeeImporter\application\services\import\ImportManager;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionLoaderException;
use EventEspresso\core\services\loaders\LoaderFactory;

if (!defined('EVENT_ESPRESSO_VERSION')) {
    exit('NO direct script access allowed');
}

/**
 *
 * Attendee_Importer_Admin_Page
 *
 * This contains the logic for setting up the Attendee_Importer Addon Admin related pages.  Any methods without PHP doc
 * comments have inline docs with parent class.
 *
 *
 * @package            Attendee_Importer_Admin_Page (attendee_importer addon)
 * @subpackage    admin/Attendee_Importer_Admin_Page.core.php
 * @author                Michael Nelson, Brent Christensen
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
        $import_type = isset($this->_req_data['type']) ? $this->_req_data['type'] : '';
        $this->_page_routes = array(
            'default' => array(
                'func' => 'main',
            ),
            'import' => array(
                'func' => 'import',
                'noheader' => true,
                'headers_sent_route' => 'show_import_step',
                'args' => ['type' => $import_type]
            ),
            'show_import_step' => array(
                'func' => 'show_import_step',
                'args' => ['type' => $import_type]
            )
        );
    }


    protected function _set_page_config()
    {

        $this->_page_config = array(
            'default' => array(
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
        wp_register_script(
            'espresso_attendee_importer_admin',
            EE_ATTENDEE_IMPORTER_ADMIN_ASSETS_URL . 'espresso_attendee_importer_admin.js',
            array('espresso_core'),
            EE_ATTENDEE_IMPORTER_VERSION,
            true
        );
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

    /**
     * Shows the list of importers available. If there's only one though, just send the user to that one.
     * @since $VID:$
     * @return void
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     */
    protected function main()
    {
        $import_manager = $this->getImportManager();
        $import_type_ui_managaers = $import_manager->loadImportTypeUiManagers();

        // If there's only one importer, don't bother asking what they want to import.
        if (count($import_type_ui_managaers) === 1) {
            $import_type = $import_type_ui_managaers->current();
            $this->show_import_step($import_type->getSlug());
        }
        
        $html = '';
        foreach ($import_type_ui_managaers as $ui_manager) {
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
     * Gets the import manager.
     * @since $VID:$
     * @return ImportManager
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    protected function getImportManager()
    {
        return LoaderFactory::getLoader()->load(
            'EventEspresso\AttendeeImporter\application\services\import\ImportManager'
        );
    }

    /**
     * Handles import step requests. If it's a post, processes the form. Otherwise, does nothing and lets
     * `show_import_step()` take care of the request.
     * @since $VID:$
     * @param $import_type
     * @throws Exception
     */
    protected function import($import_type)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $form_steps_manager = $this->getFormStepManager($import_type);
                $form_steps_manager->processForm($_POST);
            } catch (Exception $e) {
                new ExceptionStackTraceDisplay($e);
            }
        }
    }

    /**
     * Handles GET requests to show an import step.
     * @since $VID:$
     * @param $import_type
     * @throws DomainException
     * @throws EE_Error
     */
    protected function show_import_step($import_type)
    {
        try {
            $form_steps_manager = $this->getFormStepManager($import_type);
            $this->_template_args['admin_page_content'] = $form_steps_manager->displayProgressSteps()
                . $form_steps_manager->displayCurrentStepForm();
        } catch (Exception $e) {
            new ExceptionStackTraceDisplay($e);
        }

        $this->display_admin_page_with_sidebar();
    }

    /**
     * Just grabs the form step manager, based on the import type provided.
     * @param string $import_type
     * @return EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepFormManager
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws CollectionLoaderException
     */
    public function getFormStepManager($import_type)
    {
        return $this->getImportManager()
            ->getUiManager($import_type)
            ->getStepManager(EE_Admin_Page::add_query_args_and_nonce(
                ['action' => 'import',
                    'type' => $import_type
                ],
                EE_ATTENDEE_IMPORTER_ADMIN_URL
            ));
    }
}
// End of file Attendee_Importer_Admin_Page.core.php
// Location: /wp-content/plugins/eea-attendee-importer/admin/attendee_importer/Attendee_Importer_Admin_Page.core.php
