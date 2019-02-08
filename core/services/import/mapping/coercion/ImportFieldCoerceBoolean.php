<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\coercion;
use EE_Base_Class;
use EventEspresso\AttendeeImporter\core\services\import\mapping\strategies\ImportFieldCoercionInterface;

/**
 * Class ImportFieldBoolean
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportFieldCoerceBoolean implements ImportFieldCoercionInterface
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
        return filter_var($inputProperty, FILTER_VALIDATE_BOOLEAN);
    }
}
// End of file ImportFieldBoolean.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\coercion/ImportFieldBoolean.php
