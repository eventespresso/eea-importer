<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Model_Field_Base;
use EEM_Base;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportMappingCollection;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\json\JsonSerializableAndUnserializable;

/**
 * Class ImportModelConfigInterface
 *
 * Configuration details on how to map a model from source data to EE model fields.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
interface ImportModelConfigInterface extends JsonSerializableAndUnserializable
{
    /**
     * Gets the model this configuration is for
     * @since $VID:$
     * @return EEM_Base
     */
    public function getModel();

    /**
     * Gets the names of the fields on this model that are mapped.
     * @since $VID:$
     * @return string[]
     */
    public function fieldNamesMapped();

    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     * @since $VID:$
     * @return CollectionInterface|ImportFieldMap[]
     */
    public function mapping();

    /**
     * Gets the mapping info for the specified input (eg a CSV column name)
     * @since $VID:$
     * @param string $input
     * @return ImportFieldMap
     */
    public function getMappingInfoForInput($input);
}
// End of file ImportModelConfigInterface.php
// Location: EventEspresso\core\services\import/ImportModelConfigInterface.php
