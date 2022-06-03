<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

use EventEspresso\core\services\json\JsonSerializableAndUnserializable;

/**
 * Class ImportFieldMappingStrategyInterface
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
interface ImportFieldCoercionInterface extends JsonSerializableAndUnserializable
{
    /**
     * Takes the input and converts to the appropriate type.
     *
     * @param mixed $inputProperty
     * @return bool|int|string
     * @since 1.0.0.p
     */
    public function coerce($inputProperty);
}
// End of file ImportFieldMappingStrategyInterface.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\strategies/ImportFieldMappingStrategyInterface.php
