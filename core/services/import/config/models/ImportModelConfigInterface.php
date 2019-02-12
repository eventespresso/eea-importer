<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;
use EE_Model_Field_Base;
use EEM_Base;
use EventEspresso\AttendeeImporter\services\import\mapping\ImportMappingCollection;

/**
 * Class ImportModelConfigInterface
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
interface ImportModelConfigInterface
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
     * Returns the list of model fields that can be imported for this model.
     * @since $VID:$
     * @return ImportMappingCollection|EE_Model_Field_Base[]
     */
    public function fieldsMapped();

    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     * @since $VID:$
     * @return ImportMappingCollection
     */
    public function mapping();

}
// End of file ImportModelConfigInterface.php
// Location: EventEspresso\core\services\import/ImportModelConfigInterface.php
