<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\strategies;

use EE_Base_Class;

/**
 * Class ImportFieldMappingStrategyInterface
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
interface ImportFieldCoercionInterface
{
    /**
     * Takes the input and converts
     * @since $VID:$
     * @param $inputProperty
     * @param EE_Base_Class $destinationObject only used when the value of one field affects the value of another.
     * @return mixed
     */
    public function coerce($inputProperty, EE_Base_Class $destinationObject);

}
// End of file ImportFieldMappingStrategyInterface.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\strategies/ImportFieldMappingStrategyInterface.php
