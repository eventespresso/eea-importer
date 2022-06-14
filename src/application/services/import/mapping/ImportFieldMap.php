<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping;

use EE_Boolean_Field;
use EE_Error;
use EE_Foreign_Key_Field_Base;
use EE_Model_Field_Base;
use EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoercionInterface;
use EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoercionStrategyFactory;
use EventEspresso\core\services\json\JsonSerializableAndUnserializable;
use stdClass;

/**
 * Class ImportFieldMap
 *
 * Describes how to map between a property of the source data to destination model field, and how to interpret the data.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportFieldMap implements JsonSerializableAndUnserializable
{
    /**
     * @var string|null
     */
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
     *
     * @param ImportFieldCoercionStrategyFactory $coercion_factory
     * @param EE_Model_Field_Base                $destinationField
     * @param string|null                        $sourceProperty
     */
    public function __construct(
        ImportFieldCoercionStrategyFactory $coercion_factory,
        EE_Model_Field_Base $destinationField,
        ?string $sourceProperty = null
    ) {
        $this->source_property   = $sourceProperty;
        $this->destination_field = $destinationField;
        $this->coercion_factory  = $coercion_factory;
        if ($destinationField instanceof EE_Boolean_Field) {
            $this->coercion_strategy = $this->coercion_factory->create('boolean');
        }
        if ($destinationField instanceof EE_Foreign_Key_Field_Base) {
            if (in_array('State', $destinationField->get_model_names_pointed_to())) {
                $this->coercion_strategy = $this->coercion_factory->create('state');
            } elseif (in_array('Country', $destinationField->get_model_names_pointed_to())) {
                $this->coercion_strategy = $this->coercion_factory->create('country');
            }
        }
        if (! $this->coercion_strategy instanceof ImportFieldCoercionInterface) {
            $this->coercion_strategy = $this->coercion_factory->create('string');
        }
    }


    /**
     * Gets the name of the model field that is mapped.
     *
     * @return string
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function destinationFieldName(): string
    {
        return $this->destination_field->get_name();
    }


    /**
     * Gets the field the source property input maps to.
     *
     * @return EE_Model_Field_Base
     * @since 1.0.0.p
     */
    public function destinationField(): EE_Model_Field_Base
    {
        return $this->destination_field;
    }


    /**
     * @param $column
     * @since 1.0.0.p
     */
    public function map($column)
    {
        $this->source_property = $column;
    }


    /**
     * Applies the established mapping to the input to get the value to set on the model object.
     *
     * @param $input
     * @return bool|int|string
     * @since 1.0.0.p
     */
    public function applyMap($input)
    {
        return $this->coercion_strategy->coerce($input);
    }


    /**
     * Gets the name of the source's input property.
     *
     * @return string|null
     * @since 1.0.0.p
     */
    public function sourceProperty(): ?string
    {
        return $this->source_property;
    }


    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     *
     * @return stdClass
     * @since 1.0.0.p
     */
    public function toJsonSerializableData(): stdClass
    {
        $simple_obj        = new stdClass();
        $simple_obj->input = $this->source_property;
        return $simple_obj;
    }


    /**
     * Initializes this object from data
     *
     * @param mixed $data
     * @return void
     * @since 1.0.0.p
     */
    public function fromJsonSerializedData($data)
    {
        if (
            $data instanceof stdClass
            && property_exists($data, 'input')
        ) {
            $this->map($data->input);
        }
    }
}
// End of file ImportFieldMap.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping/ImportFieldMap.php
