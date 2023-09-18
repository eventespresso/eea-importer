<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

use EE_Error;
use EEM_Country;
use ReflectionException;

/**
 * Class ImportFieldString
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 */
class ImportFieldCoerceCountry implements ImportFieldCoercionInterface
{
    /**
     * @var EEM_Country
     */
    private $country_model;


    public function __construct(EEM_Country $state_model)
    {
        $this->country_model = $state_model;
    }


    /**
     * Takes the input and converts
     *
     * @param mixed $inputProperty
     * @return string|null
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function coerce($inputProperty): ?string
    {
        return $this->country_model->get_var(
            [
                [
                    'OR' => [
                        'CNT_ISO'  => $inputProperty,
                        'CNT_ISO3' => $inputProperty,
                        'CNT_name' => $inputProperty,
                    ],
                ],
                'limit' => 1,
            ],
            'CNT_ISO'
        );
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
        return 'country';
    }


    /**
     * Initializes this object from data
     *
     * @param mixed $data
     * @return boolean success
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
