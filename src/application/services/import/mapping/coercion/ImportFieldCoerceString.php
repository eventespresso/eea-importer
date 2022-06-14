<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

/**
 * Class ImportFieldString
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportFieldCoerceString implements ImportFieldCoercionInterface
{
    /**
     * Takes the input and converts
     *
     * @param mixed $inputProperty
     * @return string
     * @since 1.0.0.p
     */
    public function coerce($inputProperty): string
    {
        return (string) $inputProperty;
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
        return 'string';
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
// End of file ImportFieldString.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\coercion/ImportFieldString.php
