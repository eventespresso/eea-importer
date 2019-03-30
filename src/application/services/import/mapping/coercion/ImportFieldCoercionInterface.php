<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

use EE_Base_Class;
use EventEspresso\core\services\json\JsonSerializableAndUnserializable;

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
interface ImportFieldCoercionInterface extends JsonSerializableAndUnserializable
{
    /**
     * Takes the input and converts to the appropriate type.
     * @since $VID:$
     * @param string $inputProperty
     * @return mixed
     */
    public function coerce($inputProperty);
}
// End of file ImportFieldMappingStrategyInterface.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\strategies/ImportFieldMappingStrategyInterface.php
