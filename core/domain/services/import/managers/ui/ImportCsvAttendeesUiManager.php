<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\managers\ui;

use EventEspresso\AttendeeImporter\core\domain\services\import\managers\ImportCsvAttendeesManager;
use EventEspresso\AttendeeImporter\core\services\import\ImportTypeUiManagerInterface;
use EventEspresso\AttendeeImporter\core\services\import\ImportTypeManagerInterface;
use EventEspresso\core\services\import\JobHandler;
use EventEspresso\core\services\import\SequentialStepFormManager;

/**
 * Class ImportCsvAttendeesUiManager
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportCsvAttendeesUiManager implements ImportTypeUiManagerInterface
{


    /**
     * Gets the steps manager that corresponds to the import type.
     * @since $VID:$
     * @return SequentialStepFormManager
     */
    public function getStepManager()
    {
        // TODO: Implement getStepManager() method.
    }

    /**
     * Gets the batch system job handler that will take care of managing the import (but if it's a CLI import, this doesn't apply)
     * @since $VID:$
     * @return JobHandler
     */
    public function getBatchJobHandler()
    {
        // TODO: Implement getBatchJobHandler() method.
    }

    /**
     * Gets the ImportType that this UI is for. That's stuff relating more to the actual import rather than UI.
     * @since $VID:$
     * @return ImportTypeManagerInterface
     */
    public function getImportType()
    {
        return new ImportCsvAttendeesManager();
    }

    /**
     * Gets URL of an image that describes the import type.
     * @since $VID:$
     * @return string
     */
    public function getImage()
    {
        return $this->getImportType()->getUrlToFiles() . '/assets/images/113px-CsvDelimited001.svg.png';
    }
}
// End of file ImportCsvAttendeesUiManager.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees/ImportCsvAttendeesUiManager.php
