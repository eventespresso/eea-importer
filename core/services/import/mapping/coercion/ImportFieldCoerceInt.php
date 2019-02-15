<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\coercion;
use EE_Base_Class;

/**
 * Class ImportFieldCoerceInt
 *
 * Takes the input and converts to an integer.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportFieldCoerceInt implements ImportFieldCoercionInterface
{


    /**
     * Takes the input and converts
     * @since $VID:$
     * @param $inputProperty
     * @param EE_Base_Class $destinationObject only used when the value of one field affects the value of another.
     * @return mixed
     */
    public function coerce($inputProperty, EE_Base_Class $destinationObject)
    {
        return (int)$inputProperty;
    }

    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     * @since $VID:$
     * @return mixed
     */
    public function toJsonSerializableData()
    {
        return 'int';
    }

    /**
     * Initializes this object from data
     * @since $VID:$
     * @param mixed $data
     * @return boolean success
     */
    public function fromJsonSerializedData($data)
    {
    }
}
// End of file ImportFieldCoerceInt.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\coercion/ImportFieldCoerceInt.php
