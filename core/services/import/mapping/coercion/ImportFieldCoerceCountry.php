<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\coercion;

use EE_Error;
use EEM_Country;
use EEM_State;

/**
 * Class ImportFieldString
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
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
     * @since $VID:$
     * @param $inputProperty
     * @return int
     * @throws EE_Error
     */
    public function coerce($inputProperty)
    {
        $inputProperty = (string)$inputProperty;
        return $this->country_model->get_var(
            [
                [
                    'OR' => [
                        'CNT_ISO' => $inputProperty,
                        'CNT_ISO3' => $inputProperty,
                        'CNT_name' => $inputProperty
                    ]
                ],
                'limit' => 1
            ],
            'CNT_ISO'
        );
    }

    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     * @since $VID:$
     * @return mixed
     */
    public function toJsonSerializableData()
    {
        return 'country';
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
// End of file ImportFieldString.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\coercion/ImportFieldString.php
