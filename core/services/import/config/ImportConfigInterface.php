<?php

namespace EventEspresso\core\services\import\config;

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
    public function getModelsImported();

    /**
     * @since $VID:$
     * @param $modelName
     * @return
     */
    public function getModelConfig($modelName);
}
// End of file ImportConfig.php
// Location: EventEspresso\core\services\import/ImportConfig.php
