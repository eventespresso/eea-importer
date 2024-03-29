<?php

use EventEspresso\AttendeeImporter\application\services\import\ImportManager;
use EventEspresso\AttendeeImporter\application\services\import\ImportTypeUiManagerInterface;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\StepsManager;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidIdentifierException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\exceptions\UnexpectedEntityException;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionLoaderException;
use EventEspresso\core\services\loaders\LoaderFactory;

/**
 * Attendee_Importer_Admin_Page
 * This contains the logic for setting up the Attendee_Importer Addon Admin related pages.  Any methods without PHP doc
 * comments have inline docs with parent class.
 *
 * @package       Attendee_Importer_Admin_Page (importer addon)
 * @subpackage    admin/Attendee_Importer_Admin_Page.core.php
 * @author        Michael Nelson, Brent Christensen
 */
class Importer_Admin_Page extends EE_Admin_Page
{
    private string $import_type = '';


    /**
     * @since 1.0.0.p
     */
    protected function _init_page_props()
    {
        $this->page_slug        = ATTENDEE_IMPORTER_PG_SLUG;
        $this->page_label       = ATTENDEE_IMPORTER_LABEL;
        $this->_admin_base_url  = EE_ATTENDEE_IMPORTER_ADMIN_URL;
        $this->_admin_base_path = EE_IMPORTER_ADMIN;
        $this->import_type      = $this->request->getRequestParam('type', '');
    }


    /**
     * @since 1.0.0.p
     */
    protected function _ajax_hooks()
    {
    }


    /**
     * @since 1.0.0.p
     */
    protected function _define_page_props()
    {
        $this->_admin_page_title = ATTENDEE_IMPORTER_LABEL;
        $this->_labels           = [];
    }


    /**
     * @since 1.0.0.p
     */
    protected function _set_page_routes()
    {
        $this->_page_routes = [
            'default'          => [
                'func'               => [$this, 'main'],
                'noheader'           => true,
                'headers_sent_route' => 'default_later',
                'capability'         => 'ee_import',
            ],
            'default_later'    => [
                'func'       => [$this, 'main_later'],
                'capability' => 'ee_import',
            ],
            'import'           => [
                'func'               => [$this, 'import'],
                'noheader'           => true,
                'headers_sent_route' => 'show_import_step',
                'args'               => ['import_type' => $this->import_type],
                'capability'         => 'ee_import',
            ],
            'show_import_step' => [
                'func'       => [$this, 'show_import_step'],
                'args'       => ['import_type' => $this->import_type],
                'capability' => 'ee_import',
            ],
        ];
    }


    /**
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidIdentifierException
     * @since 1.0.0.p
     */
    protected function _set_page_config()
    {
        $help_tabs_data = [
            'importer_import_overview_help_tab' => [
                'title'    => __('Importer Overview', 'event_espresso'),
                'filename' => 'importer_import_overview',
            ],
        ];
        try {
            $step_manager = $this->getFormStepManager($this->import_type);
            $steps        = $step_manager->getSteps();
            foreach ($steps as $step) {
                if (! $step->hasHelpTab()) {
                    continue;
                }
                $help_tabs_data[ 'importer_import_' . $step->slug() ] = [
                    'title'    => $step->formName(),
                    'filename' => 'importer_import_' . $step->slug(),
                ];
            }
            $step_manager->setCurrentStepFromRequest();
        } catch (UnexpectedEntityException $e) {
            // no available importers
        }
        $this->_page_config = [
            'show_import_step' => [
                'nav'           => [
                    'label' => esc_html__('Import', 'event_espresso'),
                    'icon'  => 'dashicons-database-import',
                    'order' => 10,
                ],
                'help_tabs'     => $help_tabs_data,
                'require_nonce' => false,
            ],
            'default_later'    => [
                'require_nonce' => false,
            ],
        ];
    }


    /**
     * @since 1.0.0.p
     */
    protected function _add_screen_options()
    {
    }


    /**
     * @since 1.0.0.p
     */
    protected function _add_screen_options_default()
    {
    }


    /**
     * @since 1.0.0.p
     */
    protected function _add_feature_pointers()
    {
    }


    /**
     * @since 1.0.0.p
     */
    public function load_scripts_styles()
    {
    }


    /**
     * @since 1.0.0.p
     */
    public function admin_init()
    {
    }


    /**
     * @since 1.0.0.p
     */
    public function admin_notices()
    {
    }


    /**
     * @since 1.0.0.p
     */
    public function admin_footer_scripts()
    {
    }


    /**
     * Shows the list of importers available. If there's only one though, just send the user to that one.
     *
     * @return void
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @since 1.0.0.p
     */
    protected function main()
    {
        $import_manager          = $this->getImportManager();
        $import_type_ui_managers = $import_manager->getImportTypeUiManagers();

        // If there's only one importer, don't bother asking what they want to import.
        if (count($import_type_ui_managers) === 1) {
            $import_type = $import_type_ui_managers->current();
            wp_redirect(
                EE_Admin_Page::add_query_args_and_nonce(
                    [
                        'action' => 'import',
                        'type'   => $import_type->getSlug(),
                    ],
                    EE_ATTENDEE_IMPORTER_ADMIN_URL
                )
            );
            exit;
        }
    }


    /**
     * @throws EE_Error
     */
    protected function main_later()
    {
        $import_manager          = $this->getImportManager();
        $import_type_ui_managers = $import_manager->getImportTypeUiManagers();

        $html = EEH_HTML::h2(esc_html__('Available Importers', 'event_espresso'));
        if ($import_type_ui_managers->count() === 0) {
            $html .= EEH_HTMl::p(
                esc_html__('You do not have permission to use any of the installed importers.', 'event_espresso')
            );
        }
        foreach ($import_type_ui_managers as $ui_manager) {
            $import_type = $ui_manager->getImportType();
            $html        .= EEH_Template::display_template(
                EE_ATTENDEE_IMPORTER_ADMIN_TEMPLATE_PATH . 'importer_manager_type.template.php',
                [
                    'slug'        => $import_type->getSlug(),
                    'name'        => $import_type->getName(),
                    'description' => $import_type->getDescription(),
                    'image_url'   => $ui_manager->getImage(),
                    'steps_url'   => add_query_arg(
                        [
                            'action' => 'import',
                            'type'   => $import_type->getSlug(),
                        ],
                        EE_ATTENDEE_IMPORTER_ADMIN_URL
                    ),
                ]
            );
        }

        $this->_template_args['admin_page_content'] = $html;
        $this->display_admin_page_with_no_sidebar();
    }


    /**
     * Gets the import manager.
     *
     * @return ImportManager
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @since 1.0.0.p
     */
    protected function getImportManager(): ImportManager
    {
        return LoaderFactory::getLoader()->load(
            'EventEspresso\AttendeeImporter\application\services\import\ImportManager'
        );
    }


    /**
     * Handles import step requests. If it's a post, processes the form. Otherwise, does nothing and lets
     * `show_import_step()` take care of the request.
     *
     * @param string $import_type
     * @throws Exception
     * @throws Throwable
     * @since 1.0.0.p
     */
    protected function import(string $import_type)
    {
        if ($this->request->getServerParam('REQUEST_METHOD') === 'POST') {
            try {
                $form_steps_manager = $this->getFormStepManager($import_type);
                $form_steps_manager->processForm($this->request->postParams());
            } catch (Exception $e) {
                new ExceptionStackTraceDisplay($e);
            }
        }
    }


    /**
     * Handles GET requests to show an import step.
     *
     * @param string $import_type
     * @throws EE_Error
     * @throws ReflectionException
     * @throws Throwable
     * @since 1.0.0.p
     */
    protected function show_import_step(string $import_type)
    {
        try {
            $manager                                    = $this->getImportManager();
            $ui_manager                                 = $manager->getUiManager($import_type);
            $form_steps_manager                         = $this->getFormStepManager($import_type);
            $this->_template_args['admin_page_content'] = EEH_HTML::h2($ui_manager->getImportType()->getName()) .
                                                          $form_steps_manager->displayProgressSteps() .
                                                          $form_steps_manager->displayCurrentStepForm();
        } catch (Exception $e) {
            new ExceptionStackTraceDisplay($e);
        }

        $this->display_admin_page_with_no_sidebar();
    }


    /**
     * Just grabs the form step manager, based on the import type provided.
     *
     * @param string                            $import_type
     * @param ImportTypeUiManagerInterface|null $ui_manager
     * @return StepsManager
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws UnexpectedEntityException
     */
    protected function getFormStepManager(
        string $import_type,
        ImportTypeUiManagerInterface $ui_manager = null
    ): StepsManager {
        if (! $ui_manager instanceof ImportTypeUiManagerInterface) {
            $ui_manager = $this->getImportTypeUiManager($import_type);
        }
        return $ui_manager->getStepManager(
            EE_Admin_Page::add_query_args_and_nonce(
                [
                    'action' => 'import',
                    'type'   => $import_type,
                ],
                EE_ATTENDEE_IMPORTER_ADMIN_URL
            )
        );
    }


    /**
     * Gets the Import Type UI manager specified.
     *
     * @param string $import_type
     * @return ImportTypeUiManagerInterface
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws UnexpectedEntityException
     * @since 1.0.0.p
     */
    protected function getImportTypeUiManager(string $import_type): ImportTypeUiManagerInterface
    {
        $manager    = $this->getImportManager();
        $ui_manager = $manager->getUiManager($import_type);
        if (! $ui_manager instanceof ImportTypeUiManagerInterface) {
            $all_ui_managers = $manager->getImportTypeUiManagers();
            $all_ui_managers->rewind();
            $ui_manager = $all_ui_managers->current();
        }
        if (! $ui_manager instanceof ImportTypeUiManagerInterface) {
            throw new UnexpectedEntityException($ui_manager, ImportTypeUiManagerInterface::class);
        }
        return $ui_manager;
    }
}
// End of file Attendee_Importer_Admin_Page.core.php
// Location: /wp-content/plugins/eea-importer/admin/importer/Attendee_Importer_Admin_Page.core.php
