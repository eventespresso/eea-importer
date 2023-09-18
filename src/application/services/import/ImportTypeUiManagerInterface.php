<?php

namespace EventEspresso\AttendeeImporter\application\services\import;

use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\StepsManager;
use EventEspresso\core\libraries\batch\JobHandlerBaseClasses\JobHandler;

/**
 * Class ImportTypeUiInterface
 *
 * Interface for describing the web UI of an import. A CLI command would not use any of this.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
interface ImportTypeUiManagerInterface
{
    /**
     * Gets the steps manager that corresponds to the import type.
     *
     * @param string $base_url base URL where these steps will be shown
     *                         (used for generating links to subsequent steps)
     * @return StepsManager
     * @since 1.0.0.p
     */
    public function getStepManager(string $base_url = ''): StepsManager;


    /**
     * Gets the batch system job handler that will take care of managing the import
     * (but if it's a CLI import, this doesn't apply)
     *
     * @return JobHandler
     * @since 1.0.0.p
     */
    public function getBatchJobHandler(): JobHandler;


    /**
     * Gets the ImportType that this UI is for. That's stuff relating more to the actual import rather than UI.
     *
     * @return ImportTypeManagerInterface
     * @since 1.0.0.p
     */
    public function getImportType(): ImportTypeManagerInterface;


    /**
     * Gets URL of an image that describes the import type.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getImage(): string;
}
// End of file ImportTypeUiInterface.php
// Location: EventEspresso\core\services\import/ImportTypeUiInterface.php
