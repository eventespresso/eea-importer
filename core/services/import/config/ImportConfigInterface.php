<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config;

use EventEspresso\core\services\options\JsonWpOptionSerializableInterface;

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
interface ImportConfigInterface extends JsonWpOptionSerializableInterface
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
}
// End of file ImportConfig.php
// Location: EventEspresso\core\services\import/ImportConfig.php
