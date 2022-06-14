<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EEM_Base;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\json\JsonSerializableAndUnserializable;

/**
 * Class ImportModelConfigInterface
 *
 * Configuration details on how to map a model from source data to EE model fields.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
interface ImportModelConfigInterface extends JsonSerializableAndUnserializable
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Base
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base;


    /**
     * Gets the names of the fields on this model that are mapped.
     *
     * @return string[]
     * @since 1.0.0.p
     */
    public function fieldNamesMapped(): array;


    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     *
     * @return CollectionInterface|ImportFieldMap[]
     * @since 1.0.0.p
     */
    public function mapping();


    /**
     * Gets the mapping info for the specified input (eg a CSV column name)
     *
     * @param string $input
     * @return ImportFieldMap|null
     * @since 1.0.0.p
     */
    public function getMappingInfoForInput(string $input): ?ImportFieldMap;
}
// End of file ImportModelConfigInterface.php
// Location: EventEspresso\core\services\import/ImportModelConfigInterface.php
