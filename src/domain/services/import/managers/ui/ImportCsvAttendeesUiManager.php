<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\managers\ui;

use EventEspresso\AttendeeImporter\application\services\import\ImportTypeManagerInterface;
use EventEspresso\AttendeeImporter\application\services\import\ImportTypeUiManagerBase;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\StepsManager;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\libraries\batch\JobHandlerBaseClasses\JobHandler;

/**
 * Class ImportCsvAttendeesUiManager
 *
 * Knows about the web interface for the import. Knows which step manager, batch job to call, what image to show, etc.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportCsvAttendeesUiManager extends ImportTypeUiManagerBase
{
    protected LoaderInterface $loader;

    protected ?StepsManager $form_steps_manager = null;


    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }


    /**
     * Gets the steps manager that corresponds to the import type.
     *
     * @param string $base_url base URL where these steps will be shown (used for generating links to subsequent steps)
     * @return StepsManager
     * @since 1.0.0.p
     */
    public function getStepManager(string $base_url = ''): StepsManager
    {
        if (! $this->form_steps_manager instanceof StepsManager) {
            $this->form_steps_manager = $this->loader->getShared(
                'EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\form_handlers\StepsManager',
                [
                    // base redirect URL
                    $base_url,
                    // default step slug
                    'choose-event',
                ]
            );
            $this->form_steps_manager->buildForm();
        }
        return $this->form_steps_manager;
    }


    /**
     * Gets the batch system job handler that will take care of managing the import (but if it's a CLI import, this
     * doesn't apply)
     *
     * @return JobHandler
     * @since 1.0.0.p
     */
    public function getBatchJobHandler(): JobHandler
    {
        return $this->loader->getShared(
            'EventEspresso\AttendeeImporter\domain\services\batch\JobHandlers\AttendeeImporterBatchJob'
        );
    }


    /**
     * Gets the ImportType that this UI is for. That's stuff relating more to the actual import rather than UI.
     *
     * @return ImportTypeManagerInterface
     * @since 1.0.0.p
     */
    public function getImportType(): ImportTypeManagerInterface
    {
        return $this->loader->getShared(
            'EventEspresso\AttendeeImporter\domain\services\import\managers\ImportCsvAttendeesManager'
        );
    }


    /**
     * Gets URL of an image that describes the import type.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getImage(): string
    {
        return $this->getImportType()->getUrlToFiles() . '/assets/images/113px-CsvDelimited001.svg.png';
    }
}
// End of file ImportCsvAttendeesUiManager.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees/ImportCsvAttendeesUiManager.php
