<?php

namespace EventEspresso\AttendeeImporter\application\services\import;

use EventEspresso\AttendeeImporter\application\services\import\extractors\ImportExtractorBase;
use EventEspresso\AttendeeImporter\domain\services\commands\ImportBaseCommand;

/**
 * Class ImportTypeInterface
 *
 * Interface for classes that describe a particular job type. These are properties shared between a web UI and CLI
 * command.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
interface ImportTypeManagerInterface
{
    /**
     * Gets the name of this import type (translated).
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getName(): string;


    /**
     * Gets the slug for this import type.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getSlug(): string;


    /**
     * Gets a string of HTML describing this import type.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getDescription(): string;


    /**
     * @param $args
     * @return ImportBaseCommand
     * @since 1.0.0.p
     */
    public function getImportCommand($args): ImportBaseCommand;


    /**
     * @return ImportExtractorBase
     * @since 1.0.0.p
     */
    public function getExtractor(): ImportExtractorBase;


    /**
     * @return string
     * @since 1.0.0.p
     */
    public function getPathToFiles(): string;


    /**
     * @return string
     * @since 1.0.0.p
     */
    public function getUrlToFiles(): string;


    /**
     * Gets the name of the capability required to use this type of importer.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function cap(): string;
}
// End of file ImportTypeInterface.php
// Location: EventEspresso\core\services\import/ImportTypeInterface.php
