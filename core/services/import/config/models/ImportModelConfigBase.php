<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;
use EE_Model_Field_Base;
use EEM_Base;
use EventEspresso\AttendeeImporter\services\import\mapping\ImportFieldMap;
use EventEspresso\AttendeeImporter\services\import\mapping\ImportMappingCollection;
use EventEspresso\core\services\collections\CollectionInterface;

/**
 * Class ImportModelConfigBase
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
abstract class ImportModelConfigBase implements ImportModelConfigInterface
{
    /**
     * @var CollectionInterface|ImportFieldMap[]
     */
    protected $mapping;

    /**
     * Returns the list of model fields that can be imported for this model.
     * @since $VID:$
     * @return ImportMappingCollection|EE_Model_Field_Base[]
     */
    public function fieldsMapped()
    {
        return array_intersect_key(
            $this->getModel()->field_settings(),
            array_flip(
                $this->fieldNamesMapped()
            )
        );
    }

    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     * @since $VID:$
     * @return ImportFieldMap[]|CollectionInterface
     */
    public function mapping()
    {
        return $this->mapping;
    }
}
// End of file ImportModelConfigBase.php
// Location: EventEspresso\AttendeeImporter\core\services\import\config\models/ImportModelConfigBase.php
