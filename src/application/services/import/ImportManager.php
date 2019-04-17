<?php

namespace EventEspresso\AttendeeImporter\application\services\import;

use EventEspresso\core\services\collections\CollectionDetails;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\collections\CollectionLoader;
use EventEspresso\core\services\collections\CollectionLoaderException;
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
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Gets all the import type ui managers
     * @since $VID:$
     * @return CollectionInterface|ImportTypeUiManagerInterface[]
     * @throws CollectionLoaderException
     * @throws CollectionDetailsException
     */
    public function loadImportTypeUiManagers()
    {
        $loader = new CollectionLoader(
            new CollectionDetails(
                // collection name
                'import_type_ui_managers',
                // collection interface
                'EventEspresso\AttendeeImporter\application\services\import\ImportTypeUiManagerInterface',
                // FQCNs for classes to add (all classes within that namespace will be loaded)

                array('EventEspresso\AttendeeImporter\domain\services\import\managers\ui'),
                // filepaths to classes to add
                //                $import_source_types,
                array(),
                // file mask to use if parsing folder for files to add
                //                '*UiManager.php',
                '',
                // what to use as identifier for collection entities
                // using CLASS NAME prevents duplicates (works like a singleton)
                CollectionDetails::ID_CALLBACK_METHOD,
                'getSlug'
            )
        );

        return $loader->getCollection();
    }

    /**
     * @since $VID:$
     * @param $slug
     * @return ImportTypeUiManagerInterface
     * @throws CollectionLoaderException
     */
    public function getUiManager($slug)
    {
        $collection = $this->loadImportTypeUiManagers();
        return $collection->get($slug);
    }
}
// End of file ImportManager.php
// Location: EventEspresso\core\domain\services\import/ImportManager.php
