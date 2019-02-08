<?php

namespace EventEspresso\AttendeeImporter\core\services\import;

/**
 * Class ImportTypeUiInterface
 *
 * Interface for describing the web UI of an import. A CLI command would not use any of this.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
interface ImportTypeUiManagerInterface
{

    /**
     * Gets the steps manager that corresponds to the import type.
     * @since $VID:$
     * @return SequentialStepFormManager
     */
    public function getStepManager();

    /**
     * Gets the batch system job handler that will take care of managing the import (but if it's a CLI import, this doesn't apply)
     * @since $VID:$
     * @return JobHandler
     */
    public function getBatchJobHandler();

    /**
     * Gets the ImportType that this UI is for. That's stuff relating more to the actual import rather than UI.
     * @since $VID:$
     * @return ImportTypeManagerInterface
     */
    public function getImportType();

    /**
     * Gets URL of an image that describes the import type.
     * @since $VID:$
     * @return string
     */
    public function getImage();

}
// End of file ImportTypeUiInterface.php
// Location: EventEspresso\core\services\import/ImportTypeUiInterface.php
