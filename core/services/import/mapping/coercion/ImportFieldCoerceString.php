<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\coercion;
use EE_Base_Class;
use EventEspresso\AttendeeImporter\core\services\import\mapping\strategies\ImportFieldCoercionInterface;

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
class ImportFieldCoerceString implements ImportFieldCoercionInterface
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
        return (string)$inputProperty;
    }
}
// End of file ImportFieldString.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\coercion/ImportFieldString.php
