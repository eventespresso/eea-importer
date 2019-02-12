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

    /**
     * @var boolean indicating if this needs to be saved.
     */
    protected $dirty;

    public function __construct()
    {
        $this->loadFromDb();
        add_action('shutdown', [$this,'saveToDb']);
    }

    /**
     * @since $VID:$
     */
    public function loadFromDb()
    {
        $option = get_option($this->getWpOptionName());
        if( $option ){
            $json = json_decode($option, true);
            if(is_array($json)){
                $this->fromArray($json);
            }
        }
    }

    /**
     * @since $VID:$
     * @return boolean
     */
    public function saveToDb()
    {
        if($this->dirty()) {
            $data = $this->toArray();
            return update_option($this->getWpOptionName(), wp_json_encode($data), false);
        }
    }

    /**
     * @since $VID:$
     * @return array
     */
    public function toArray()
    {
        return [];
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

    /**
     * @since $VID:$
     * @return bool
     */
    public function dirty()
    {
        return $this->dirty;
    }

    /**
     * @since $VID:$
     * @param bool $dirty
     */
    protected function setDirty($dirty = true)
    {
        $this->dirty = (bool)$dirty;
    }

}
// End of file ImportConfigBase.php
// Location: EventEspresso\core\services\import\config/ImportConfigBase.php
