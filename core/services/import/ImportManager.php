<?php

namespace EventEspresso\AttendeeImporter\core\services\import;

use EventEspresso\core\services\collections\CollectionDetails;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\collections\CollectionLoader;
use EventEspresso\core\services\loaders\Loader;

/**
 * Class ImportManager
 *
 * Is aware of what different Event Espresso importers are available.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportManager
{
    protected $loader;
    public function __construct( Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Gets all the import type ui managers
     * @since $VID:$
     * @return CollectionInterface
     * @throws \EventEspresso\core\services\collections\CollectionLoaderException
     */
    public function loadImportTypeUiManagers()
    {
        $import_domain_filepath = wp_normalize_path(EE_ATTENDEE_IMPORTER_PATH . 'core/domain/services/import/managers/ui');
        $import_source_types = glob($import_domain_filepath . '*', GLOB_ONLYDIR );

        $loader = new CollectionLoader(
            new CollectionDetails(
                // collection name
                'import_type_ui_managers',
                // collection interface
                'EventEspresso\AttendeeImporter\core\services\import\ImportTypeUiManagerInterface',
                // FQCNs for classes to add (all classes within that namespace will be loaded)

                array('EventEspresso\AttendeeImporter\core\domain\services\import\managers\ui'),
                // filepaths to classes to add
//                $import_source_types,
                array(),
                // file mask to use if parsing folder for files to add
//                '*UiManager.php',
                '',
                // what to use as identifier for collection entities
                // using CLASS NAME prevents duplicates (works like a singleton)
                CollectionDetails::ID_CLASS_NAME
            )
        );

        return $loader->getCollection();




        $managers = [];
        $import_domain_filepath = wp_normalize_path(EE_ATTENDEE_IMPORTER_PATH . 'core/domain/services/import/');
        $ui_manager_filepaths = glob($import_domain_filepath . '/*/*/*UiManager.php');

        $import_source_types = glob($import_domain_filepath . '*', GLOB_ONLYDIR|GLOB_MARK );
        foreach ($import_source_types as $import_source_type) {
            $type_dir = wp_normalize_path( $import_source_type);
            $subtypes = glob($type_dir . '*', GLOB_ONLYDIR|GLOB_MARK );
            foreach ($subtypes as $subtype_dir) {
                $subtype_dir = wp_normalize_path( $subtype_dir);
                $files_in_dir = glob($subtype_dir . '/*UiManager.php');
                if (!empty($files_in_dir)) {
                    $ui_manager_file = reset($files_in_dir);
                    $fqcn = 'EventEspresso\AttendeeImporter\core\domain\services\import\\'
                        . $import_source_type
                        . '\\'
                        . $subtype_dir
                        . '\\'
                        . str_replace('.php', '', $ui_manager_file);
                    $managers[] = $this->loader->getShared($fqcn);
                }
            }
        }
        return apply_filters(
            'FHEE__EventEspresso_core_services_import_ImportManager__loadManagers',
            $managers
        );
    }

}
// End of file ImportManager.php
// Location: EventEspresso\core\domain\services\import/ImportManager.php
