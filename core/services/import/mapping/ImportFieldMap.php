<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping;

use EE_Base_Class;
use EE_Error;
use EE_Model_Field_Base;
use EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoerceString;
use EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoercionInterface;
use EventEspresso\core\services\json\JsonSerializableAndUnserializable;
use stdClass;

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
class ImportFieldMap implements JsonSerializableAndUnserializable
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
        EE_Model_Field_Base $destinationField,
        $sourceProperty = null,
        ImportFieldCoercionInterface $coercionStrategy = null
    ){
        $this->sourceProperty = $sourceProperty;
        $this->destinationField = $destinationField;
        if(! $coercionStrategy instanceof ImportFieldCoercionInterface) {
            $coercionStrategy = new ImportFieldCoerceString();
        }
        $this->coercionStrategy = $coercionStrategy;
    }

    /**
     * Gets the name of the model field that is mapped.
     * @since $VID:$
     * @return string
     * @throws EE_Error
     */
    public function destinationFieldName()
    {
        return $this->destinationField->get_name();
    }

    /**
     * Gets the field the source property input maps to.
     * @since $VID:$
     * @return EE_Model_Field_Base
     */
    public function destinationField()
    {
        return $this->destinationField;
    }

    /**
     * @since $VID:$
     * @param $column
     * @param $coercion_strategy_name
     */
    public function map($column, $coercion_strategy_name = '')
    {
        $this->sourceProperty = $column;
        $this->coercionStrategy = new ImportFieldCoerceString();
    }

    /**
     * Uses the input value, and the established mapping, to apply the input to destination object's field.
     * @since $VID:$
     * @param $input
     * @param EE_Base_Class $destinationObject
     * @throws EE_Error
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

    /**
     * Gets the name of the source's input property.
     * @since $VID:$
     * @return string
     */
    public function sourceProperty()
    {
        return $this->sourceProperty;
    }

    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     * @since $VID:$
     * @return mixed
     */
    public function toJsonSerializableData()
    {
        $simple_obj = new stdClass();
        $simple_obj->input = $this->sourceProperty;
        $simple_obj->coercionStrategy = $this->coercionStrategy->toJsonSerializableData();
        return $simple_obj;
    }

    /**
     * Initializes this object from data
     * @since $VID:$
     * @param mixed $data
     * @return boolean success
     */
    public function fromJsonSerializedData($data)
    {
        if($data instanceof stdClass
        && property_exists($data, 'input')
        && property_exists($data, 'coercionStrategy')) {
            $this->map($data->input, $data->coercionStrategy);
        }
    }
}
// End of file ImportFieldMap.php
// Location: EventEspresso\AttendeeImporter\services\import\mapping/ImportFieldMap.php
