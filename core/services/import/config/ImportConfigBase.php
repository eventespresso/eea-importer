<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config;

use EventEspresso\AttendeeImporter\core\services\import\config\models\ImportModelConfigInterface;

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

    public function __construct()
    {
    }


    /**
     * @since $VID:$
     * @param string $model_name
     * @return ImportModelConfigInterface[]
     */
    public function getModelConfigs($model_name)
    {
        return $this->model_configs[$model_name];
    }

    /**
     * @since $VID:$
     * @return ImportModelConfigInterface[]
     */
    public function getAllModelConfigs()
    {
        return $this->model_configs;
    }
}
// End of file ImportConfigBase.php
// Location: EventEspresso\core\services\import\config/ImportConfigBase.php
