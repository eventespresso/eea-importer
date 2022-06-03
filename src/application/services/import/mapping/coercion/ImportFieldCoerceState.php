<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

use EE_Error;
use EEM_State;

/**
 * Class ImportFieldString
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         1.0.0.p
 *
 */
class ImportFieldCoerceState implements ImportFieldCoercionInterface
{
    /**
     * @var EEM_State
     */
    private $state_model;

    public function __construct(EEM_State $state_model)
    {
        $this->state_model = $state_model;
    }


    /**
     * Takes the input and converts
     *
     * @param mixed $inputProperty
     * @return int
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function coerce($inputProperty): int
    {
        return (int) $this->state_model->get_var(
            [
                [
                    'OR' => [
                        'STA_abbrev' => $inputProperty,
                        'STA_name' => $inputProperty,
                        'STA_ID' => $inputProperty
                    ]
                ],
                'limit' => 1
            ],
            'STA_ID'
        );
    }

    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     *
     * @return string
     *@since 1.0.0.p
     */
    public function toJsonSerializableData(): string
    {
        return 'state';
    }

    /**
     * Initializes this object from data
     * @since 1.0.0.p
     * @param mixed $data
     * @return bool
     */
    public function fromJsonSerializedData($data): bool
    {
        // TODO: Implement fromJsonSerializedData() method.
        return true;
    }
}
// End of file ImportFieldString.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\coercion/ImportFieldString.php
