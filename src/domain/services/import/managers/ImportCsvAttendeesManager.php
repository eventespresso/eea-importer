<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\managers;

use EventEspresso\AttendeeImporter\domain\services\commands\ImportBaseCommand;
use EventEspresso\AttendeeImporter\application\services\import\extractors\ImportExtractorBase;
use EventEspresso\AttendeeImporter\application\services\import\ImportTypeManagerInterface;
use EventEspresso\core\services\loaders\LoaderInterface;

/**
 * Class ImportCsvAttendeesManager
 *
 * Knows about importing attendees from a CSV. UI agnostic, this class is all about getting the info and getting the
 * job done.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportCsvAttendeesManager implements ImportTypeManagerInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ImportExtractorBase
     */
    protected $extractor;


    /**
     * ImportCsvAttendeesManager constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {

        $this->loader = $loader;
    }

    /**
     * Gets the name of this import type (translated).
     * @since $VID:$
     * @return string
     */
    public function getName()
    {
        return esc_html__('Import Contacts from CSV File', 'event_espresso');
    }

    /**
     * Gets a string of HTML describing this import type.
     * @since $VID:$
     * @return string
     */
    public function getDescription()
    {
        return esc_html__(
            // @codingStandardsIgnoreStart
            'Upload a CSV file, where each row contains contact, registrations, transaction and payment information.',
            // @codingStandardsIgnoreEnd
            'event_espresso'
        );
    }

    /**
     * Gets the slug for this import type.
     * @since $VID:$
     * @return string
     */
    public function getSlug()
    {
        return 'csv-attendee';
    }

    public function getPathToFiles()
    {
        return EE_ATTENDEE_IMPORTER_PATH . 'domain/services/import/csv/attendees';
    }

    public function getUrlToFiles()
    {
        return EE_ATTENDEE_IMPORTER_URL . 'domain/services/import/csv/attendees';
    }

    /**
     * @since $VID:$
     * @return ImportBaseCommand
     */
    public function getImportCommand($args)
    {
        return $this->loader->getNew(
            'EventEspresso\AttendeeImporter\domain\services\commands\ImportCommand',
            $args
        );
    }

    /**
     * @since $VID:$
     * @return ImportExtractorBase
     */
    public function getExtractor()
    {
        if (! $this->extractor instanceof ImportExtractorBase) {
            $this->extractor = $this->loader->getShared('EventEspresso\AttendeeImporter\application\services\import\extractors\ImportExtractorCsv');
        }
        return $this->extractor;
    }
}
// End of file ImportCsvAttendeesManager.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees/ImportCsvAttendeesManager.php
