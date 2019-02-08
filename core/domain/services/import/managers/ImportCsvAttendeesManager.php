<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\managers;

use EventEspresso\AttendeeImporter\core\services\import\ImportTypeManagerInterface;

/**
 * Class ImportCsvAttendeesManager
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportCsvAttendeesManager implements ImportTypeManagerInterface
{


    /**
     * @since $VID:$
     * @return ImportUnitCommandInterface
     */
    public function getImportCommand()
    {
        // TODO: Implement getImportCommand() method.
    }

    /**
     * @since $VID:$
     * @return mixed
     */
    public function getConfig()
    {
        // TODO: Implement getConfig() method.
    }

    public function saveConfig()
    {
        // TODO: Implement saveConfig() method.
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
        return EE_ATTENDEE_IMPORTER_PATH . 'core/domain/services/import/csv/attendees';
    }

    public function getUrlToFiles()
    {
        return EE_ATTENDEE_IMPORTER_URL . 'core/domain/services/import/csv/attendees';
    }
}
// End of file ImportCsvAttendeesManager.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees/ImportCsvAttendeesManager.php
