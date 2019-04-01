<?php if (! defined('EVENT_ESPRESSO_VERSION')) {
    exit();
}
// define the plugin directory path and URL
define('EE_ATTENDEE_IMPORTER_BASENAME', plugin_basename(EE_ATTENDEE_IMPORTER_PLUGIN_FILE));
define('EE_ATTENDEE_IMPORTER_PATH', plugin_dir_path(__FILE__));
define('EE_ATTENDEE_IMPORTER_URL', plugin_dir_url(__FILE__));
define(
    'EE_ATTENDEE_IMPORTER_ADMIN',
    EE_ATTENDEE_IMPORTER_PATH . 'ui' . DS . 'admin' . DS . 'attendee_importer' . DS
);

/**
 * Class  EE_Attendee_Importer
 *
 * @package     Event Espresso
 * @subpackage  eea-attendee-importer
 * @author      Brent Christensen
 */
class EE_Attendee_Importer extends EE_Addon
{

    /**
     * EE_Attendee_Importer constructor.
     * !!! IMPORTANT !!!
     * you should NOT run any logic in the constructor for addons
     * because addon construction should NOT result in code execution.
     * Successfully registering the addon via the EE_Register_Addon API
     * should be the ONLY way that code should execute.
     * This prevents errors happening due to incompatibilities between addons and core.
     * If you run code here, but core deems it necessary to NOT activate this addon,
     * then fatal errors could happen if this code attempts to reference
     * other classes that do not exist because they have not been loaded.
     * That said, it's still a better idea to any extra code
     * in the after_registration() method below.
     */
    // public function __construct()
    // {
    //     // if for some reason you absolutely, positively NEEEED a constructor...
    //     // then at least make sure to call the parent class constructor,
    //     // or things may not operate as expected.
    //     parent::__construct();
    // }



    /**
     * !!! IMPORTANT !!!
     * this is not the place to perform any logic or add any other filter or action callbacks
     * this is just to bootstrap your addon; and keep in mind the addon might be DE-registered
     * in which case your callbacks should probably not be executed.
     * EED_Attendee_Importer is typically the best place for most filter and action callbacks
     * to be placed (relating to the primary business logic of your addon)
     * IF however for some reason, a module does not work because you have some logic
     * that needs to run earlier than when the modules load,
     * then please see the after_registration() method below.
     *
     * @throws \EE_Error
     */
    public static function register_addon()
    {
        // register addon via Plugin API
        EE_Register_Addon::register(
            'Attendee_Importer',
            array(
                'version'               => EE_ATTENDEE_IMPORTER_VERSION,
                'plugin_slug'           => 'espresso_attendee_importer',
                'min_core_version'      => EE_ATTENDEE_IMPORTER_CORE_VERSION_REQUIRED,
                'main_file_path'        => EE_ATTENDEE_IMPORTER_PLUGIN_FILE,
                'namespace'             => array(
                    'FQNS' => 'EventEspresso\AttendeeImporter',
                    'DIR'  => __DIR__,
                ),
                'admin_path'            => EE_ATTENDEE_IMPORTER_ADMIN,
                'admin_callback'        => '',
                'autoloader_paths'      => array(
                    'Attendee_Importer_Admin_Page'      => EE_ATTENDEE_IMPORTER_ADMIN . 'Attendee_Importer_Admin_Page.core.php',
                    'Attendee_Importer_Admin_Page_Init' => EE_ATTENDEE_IMPORTER_ADMIN . 'Attendee_Importer_Admin_Page_Init.core.php',
                ),
                'class_paths' => EE_ATTENDEE_IMPORTER_PATH . 'domain' . DS . 'entities' . DS . 'db_classes',
                'model_paths' => EE_ATTENDEE_IMPORTER_PATH . 'domain' . DS . 'entities' . DS . 'db_models',
                'module_paths'          => [
                    EE_ATTENDEE_IMPORTER_PATH . 'domain' . DS . 'services' . DS . 'modules' . DS . 'EED_Attendee_Importer.module.php'
                ],
                // if plugin update engine is being used for auto-updates. not needed if PUE is not being used.
                'pue_options'           => array(
                    'pue_plugin_slug' => 'eea-attendee-importer',
                    'plugin_basename' => EE_ATTENDEE_IMPORTER_BASENAME,
                    'checkPeriod'     => '24',
                    'use_wp_update'   => false,
                ),
                'capabilities'          => array(
                    'administrator' => array(
                        'edit_thing',
                        'edit_things',
                        'edit_others_things',
                        'edit_private_things',
                    ),
                ),
                'capability_maps'       => array(
                    'EE_Meta_Capability_Map_Edit' => array(
                        'edit_thing',
                        array( 'Attendee_Importer_Thing', 'edit_things', 'edit_others_things', 'edit_private_things' ),
                    ),
                ),
            )
        );
    }



    /**
     * @return void;
     */
    public function after_registration()
    {
        EE_Psr4AutoloaderInit::psr4_loader()->addNamespace('EventEspresso\AttendeeImporter', EE_ATTENDEE_IMPORTER_PATH);
        $attendee_mover_dependencies = array(
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\StepsManager'                            => array(
                null,
                null,
                null,
                null,
                null,
                'EE_Request' => EE_Dependency_Map::load_from_cache,
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\UploadCsv'                             => array(
                'EE_Registry' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\MapCsvColumns'                            => array(
                'EE_Registry' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\ChooseEvent'                           => array(
                'EE_Registry' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\ChooseTicket'                          => array(
                'EE_Registry' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
                '\EventEspresso\AttendeeImporter\domain\services\import\managers\ui\ImportCsvAttendeesUiManager' => EE_Dependency_Map::load_from_cache,
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\Complete'                                => array(
                'EE_Registry' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
            ),
            'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms\MapCsvColumnsSubform'                              => [
                array(),
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
            ],
            // commands handlers
            'EventEspresso\AttendeeImporter\domain\services\commands\ImportCommandHandler' => [
                'EventEspresso\core\services\commands\CommandBusInterface' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\commands\CommandFactoryInterface' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
            ],
            'EventEspresso\AttendeeImporter\domain\services\commands\ImportAttendeeCommandHandler' => [
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\application\services\import\ImportManager' => [
                'EventEspresso\core\services\loaders\Loader' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\domain\services\import\managers\ui\ImportCsvAttendeesUiManager' => [
                'EventEspresso\core\services\loaders\Loader' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\domain\services\batch\JobHandlers\AttendeeImporterBatchJob' => [
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\options\JsonWpOptionManager' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\AttendeeImporter\domain\services\import\managers\ImportCsvAttendeesManager' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap' => [
                'EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoercionStrategyFactory' => EE_Dependency_Map::load_from_cache,
            ],
            'EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoercionStrategyFactory' => [
                'EventEspresso\core\services\loaders\LoaderInterface' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoerceState' => [
                'EEM_State' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoerceCountry' => [
                'EEM_Country' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\domain\services\import\managers\ImportCsvAttendeesManager' => [
                'EventEspresso\core\services\loaders\LoaderInterface' => EE_Dependency_Map::load_from_cache
            ],
            'EventEspresso\AttendeeImporter\domain\services\import\managers\ui\ImportCsvAttendeesUiManager' => [
                'EventEspresso\core\services\loaders\LoaderInterface' => EE_Dependency_Map::load_from_cache
            ],
        );
        foreach ($attendee_mover_dependencies as $class => $dependencies) {
            if (! EE_Dependency_Map::register_dependencies($class, $dependencies)) {
                EE_Error::add_error(
                    sprintf(
                        esc_html__('Could not register dependencies for "%1$s"', 'event_espresso'),
                        $class
                    ),
                    __FILE__,
                    __FUNCTION__,
                    __LINE__
                );
            }
        }
    }
}
// End of file EE_Attendee_Importer.class.php
// Location: wp-content/plugins/eea-attendee-importer/EE_Attendee_Importer.class.php