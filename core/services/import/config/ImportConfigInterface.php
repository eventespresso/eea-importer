<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config;

/**
 * Class ImportConfig
 *
 * Interface for describing import configurations.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
interface ImportConfigInterface
{
    /**
     * @since $VID:$
     * @return string[]
     */
    public function getModelNamesImported();

    /**
     * @since $VID:$
     * @param $model_name
     * @return ImportModelConfigInterface[]
     */
    public function getModelConfigs($model_name);

    /**
     * Gets the name of the WordPress option where this JSON data will be stored.
     * @since $VID:$
     * @return string
     */
    public function getWpOptionName();

    /**
     * @since $VID:$
     */
    public function loadFromDb();

    /**
     * @since $VID:$
     * @return boolean
     */
    public function saveToDb();

    /**
     * Converts this object into a PHP array
     * @since $VID:$
     * @return array
     */
    public function toArray();

    /**
     * Populates this object from a PHP array
     * @since $VID:$
     * @param array $data
     */
    public function fromArray(array $data);
}
// End of file ImportConfig.php
// Location: EventEspresso\core\services\import/ImportConfig.php
