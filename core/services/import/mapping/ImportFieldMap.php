<?php

namespace EventEspresso\AttendeeImporter\services\import\mapping;
use EE_Base_Class;
use EE_Model_Field_Base;
use EventEspresso\AttendeeImporter\core\services\import\mapping\strategies\ImportFieldCoercionInterface;

/**
 * Class ImportFieldMap
 *
 * Describes how to map between a property of the source data to destination model field, and how to interpret the data.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportFieldMap
{
    protected $sourceProperty;

    /**
     * @var EE_Model_Field_Base
     */
    protected $destinationField;

    /**
     * @var ImportFieldCoercionInterface
     */
    protected $coercionStrategy;

    /**
     * ImportFieldMap constructor.
     * @param $sourceProperty
     * @param EE_Model_Field_Base $destinationField
     * @param ImportFieldCoercionInterface $coercionStrategy
     */
    public function __construct(
        $sourceProperty,
        EE_Model_Field_Base $destinationField,
        ImportFieldCoercionInterface $coercionStrategy
    ){
        $this->sourceProperty = $sourceProperty;
        $this->destinationField = $destinationField;
        $this->coercionStrategy = $coercionStrategy;
    }

    /**
     * Uses the input value
     * @since $VID:$
     * @param $input
     * @param EE_Base_Class $destinationObject
     * @throws \EE_Error
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function applyMap($input, EE_Base_Class $destinationObject) {
        $destinationObject->set(
            $this->destinationField->get_name(),
            $this->coercionStrategy->coerce($input, $destinationObject)
        );
    }
}
// End of file ImportFieldMap.php
// Location: EventEspresso\AttendeeImporter\services\import\mapping/ImportFieldMap.php
