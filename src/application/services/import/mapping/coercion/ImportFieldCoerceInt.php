<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

/**
 * Class ImportFieldCoerceInt
 *
 * Takes the input and converts to an integer.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportFieldCoerceInt implements ImportFieldCoercionInterface
{
    /**
     * Takes the input and converts
     *
     * @param mixed $inputProperty
     * @return int
     * @since 1.0.0.p
     */
    public function coerce($inputProperty): int
    {
        return (int) $inputProperty;
    }


    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     *
     * @return string
     * @since 1.0.0.p
     */
    public function toJsonSerializableData(): string
    {
        return 'int';
    }


    /**
     * Initializes this object from data
     *
     * @param mixed $data
     * @return bool
     * @since 1.0.0.p
     */
    public function fromJsonSerializedData($data): bool
    {
        // TODO: Implement fromJsonSerializedData() method.
        return true;
    }
}
// End of file ImportFieldCoerceInt.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\coercion/ImportFieldCoerceInt.php
