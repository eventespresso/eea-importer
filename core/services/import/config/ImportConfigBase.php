<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config;

use EventEspresso\AttendeeImporter\core\services\import\config\models\ImportModelConfigInterface;
use EventEspresso\core\services\collections\CollectionInterface;

/**
 * Class ImportConfigBase
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
abstract class ImportConfigBase implements ImportConfigInterface
{
    /**
     * @var ImportModelConfigInterface[]
     */
    protected $model_configs;

    /**
     * @var bool
     */
    protected $model_configs_initialized = false;

    /**
     * @since $VID:$
     * @return ImportModelConfigInterface[]
     */
    public function getModelConfigs()
    {
        $this->checkModelConfigsInitialized();
        return $this->model_configs;
    }

    protected function checkModelConfigsInitialized()
    {
        if (! $this->model_configs_initialized) {
            $this->model_configs = $this->initializeModelConfigCollection();
            $this->model_configs_initialized = true;
        }
    }

    /**
     * Gets the collection of model configs for this import configuration.
     * @since $VID:$
     * @return CollectionInterface|ImportModelConfigInterface[]
     */
    abstract protected function initializeModelConfigCollection();
}
// End of file ImportConfigBase.php
// Location: EventEspresso\core\services\import\config/ImportConfigBase.php
