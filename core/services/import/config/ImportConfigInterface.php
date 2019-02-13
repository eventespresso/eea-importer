<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config;

use EventEspresso\AttendeeImporter\core\services\import\config\models\ImportModelConfigInterface;
use EventEspresso\core\services\collections\CollectionInterface;
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
     * @return CollectionInterface|ImportModelConfigInterface[]
     */
    public function getModelConfigs();
}
// End of file ImportConfig.php
// Location: EventEspresso\core\services\import/ImportConfig.php
