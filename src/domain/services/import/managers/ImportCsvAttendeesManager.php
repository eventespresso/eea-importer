<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\managers;

use EE_Error;
use EEH_Autoloader;
use EventEspresso\AttendeeImporter\application\services\import\extractors\ImportExtractorBase;
use EventEspresso\AttendeeImporter\application\services\import\ImportTypeManagerInterface;
use EventEspresso\AttendeeImporter\domain\services\commands\ImportBaseCommand;
use EventEspresso\core\services\loaders\LoaderInterface;

/**
 * Class ImportCsvAttendeesManager
 *
 * Knows about importing attendees from a CSV. UI agnostic, this class is all about getting the info and getting the
 * job done.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
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
     *
     * @param LoaderInterface $loader
     * @throws EE_Error
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
        EEH_Autoloader::register_business_classes();
    }


    /**
     * Gets the name of this import type (translated).
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getName(): string
    {
        return esc_html__('Attendee Importer', 'event_espresso');
    }


    /**
     * Gets a string of HTML describing this import type.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getDescription(): string
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
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getSlug(): string
    {
        return 'csv-attendee';
    }


    /**
     * @return string
     * @since 1.0.0.p
     */
    public function getPathToFiles(): string
    {
        return EE_IMPORTER_PATH . 'domain/services/import/csv/attendees';
    }


    /**
     * @return string
     * @since 1.0.0.p
     */
    public function getUrlToFiles(): string
    {
        return EE_IMPORTER_URL . 'domain/services/import/csv/attendees';
    }


    /**
     * @param array $args
     * @return ImportBaseCommand
     * @since 1.0.0.p
     */
    public function getImportCommand($args): ImportBaseCommand
    {
        return $this->loader->getNew(
            'EventEspresso\AttendeeImporter\domain\services\commands\ImportCommand',
            $args
        );
    }


    /**
     * @return ImportExtractorBase
     * @since 1.0.0.p
     */
    public function getExtractor(): ImportExtractorBase
    {
        if (! $this->extractor instanceof ImportExtractorBase) {
            $this->extractor = $this->loader->getShared(
                'EventEspresso\AttendeeImporter\application\services\import\extractors\ImportExtractorCsv'
            );
        }
        return $this->extractor;
    }


    /**
     * @return string
     * @since 1.0.0.p
     */
    public function cap(): string
    {
        return 'ee_import_attendees';
    }
}
// End of file ImportCsvAttendeesManager.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees/ImportCsvAttendeesManager.php
