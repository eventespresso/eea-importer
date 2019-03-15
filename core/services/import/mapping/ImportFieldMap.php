<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping;

use EE_Base_Class;
use EE_Boolean_Field;
use EE_Error;
use EE_Foreign_Key_Int_Field;
use EE_Model_Field_Base;
use EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoerceString;
use EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoercionInterface;
use EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoercionStrategyFactory;
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
    protected $source_property;

    /**
     * @var EE_Model_Field_Base
     */
    protected $destination_field;

    /**
     * @var ImportFieldCoercionInterface
     */
    protected $coercion_strategy;
    /**
     * @var ImportFieldCoercionStrategyFactory
     */
    private $coercion_factory;

    /**
     * ImportFieldMap constructor.
     * @param $sourceProperty
     * @param EE_Model_Field_Base $destinationField
     * @param ImportFieldCoercionInterface $coercionStrategy
     */
    public function __construct(
        ImportFieldCoercionStrategyFactory $coercion_factory,
        EE_Model_Field_Base $destinationField,
        $sourceProperty = null
    )
    {
        $this->source_property = $sourceProperty;
        $this->destination_field = $destinationField;
        $this->coercion_factory = $coercion_factory;
        if ($destinationField instanceof EE_Boolean_Field) {
            $this->coercion_strategy = $this->coercion_factory->create('boolean');
        }
        if ($destinationField instanceof EE_Foreign_Key_Int_Field && $destinationField->get_model_name()) {
            if (in_array('State', $destinationField->get_model_names_pointed_to())) {
                $this->coercion_factory->create('state');
            } elseif (in_array('Country', $destinationField->get_model_names_pointed_to())) {
                $this->coercion_factory->create('country');
            }
        }
        if (!$this->coercion_strategy instanceof ImportFieldCoercionInterface) {
            $this->coercion_strategy = $this->coercion_factory->create('string');
        }
    }

    /**
     * Gets the name of the model field that is mapped.
     * @since $VID:$
     * @return string
     * @throws EE_Error
     */
    public function destinationFieldName()
    {
        return $this->destination_field->get_name();
    }

    /**
     * Gets the field the source property input maps to.
     * @since $VID:$
     * @return EE_Model_Field_Base
     */
    public function destinationField()
    {
        return $this->destination_field;
    }

    /**
     * @since $VID:$
     * @param $column
     * @param $coercion_strategy_name
     */
    public function map($column)
    {
        $this->source_property = $column;
    }

    /**
     * Applies the established mapping to the input to get the value to set on the model object.
     * @since $VID:$
     * @param $input
     * @param EE_Base_Class $destinationObject
     * @throws EE_Error
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function applyMap($input)
    {
        return $this->coercion_strategy->coerce($input);
    }

    /**
     * Gets the name of the source's input property.
     * @since $VID:$
     * @return string
     */
    public function sourceProperty()
    {
        return $this->source_property;
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
        $simple_obj->input = $this->source_property;
        $simple_obj->coercion_strategy = $this->coercion_strategy->toJsonSerializableData();
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
        if ($data instanceof stdClass
            && property_exists($data, 'input')
            && property_exists($data, 'coercion_strategy')) {
            $this->map($data->input);
            $this->coercion_strategy = $this->coercion_factory->create($data->coercion_strategy);
        }
    }
}
// End of file ImportFieldMap.php
// Location: EventEspresso\AttendeeImporter\services\import\mapping/ImportFieldMap.php
