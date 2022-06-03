<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\collections\Collection;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\collections\CollectionLoaderException;
use EventEspresso\core\services\loaders\LoaderFactory;
use stdClass;

/**
 * Class ImportModelConfigBase
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
abstract class ImportModelConfigBase implements ImportModelConfigInterface
{
    /**
     * @var CollectionInterface|ImportFieldMap[]
     */
    protected $mapping;

    /**
     * @var bool
     */
    protected $initialized = false;


    /**
     * ImportModelConfigBase constructor.
     *
     * @throws InvalidInterfaceException
     */
    public function __construct()
    {
        $this->setNewMap();
    }


    /**
     * Clears the mapping collection.
     *
     * @throws InvalidInterfaceException
     * @since 1.0.0.p
     */
    protected function setNewMap()
    {
        $this->mapping =
            new Collection('EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap');
    }


    /**
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @since 1.0.0.p
     */
    protected function checkInitialized()
    {
        if (! $this->initialized) {
            $this->init();
        }
    }


    /**
     * Returns the list of model fields that can be imported for this model.
     *
     * @return void
     * @throws EE_Error
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @since 1.0.0.p
     */
    public function init()
    {
        foreach ($this->fieldNamesMapped() as $field_name) {
            $field = $this->getModel()->field_settings_for($field_name);
            $this->mapping->add(
                LoaderFactory::getLoader()->getNew(
                    'EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap',
                    [
                        null,
                        $field,
                    ]
                ),
                $field_name
            );
        }
        $this->initialized = true;
    }


    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     *
     * @return CollectionInterface|ImportFieldMap[]
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function mapping()
    {
        $this->checkInitialized();
        return $this->mapping;
    }


    /**
     * Shortcut to get the name of the model this affects.
     *
     * @return string
     * @since 1.0.0.p
     */
    public function getModelName(): string
    {
        return $this->getModel()->get_this_model_name();
    }


    public function map($input_column, $field_name)
    {
        $map_obj = $this->mapping->get($field_name);
        $map_obj->map($input_column);
    }


    /**
     * Clears all the previously mapped fields. Useful if there is new mapping information.
     *
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @throws InvalidInterfaceException
     * @since 1.0.0.p
     */
    public function clearMapping()
    {
        $this->setNewMap();
        $this->init();
    }


    /**
     * Gets the mapping info for the specified input (eg a CSV column name),
     * or null if the input source property isn't mapped.
     *
     * @param string $input
     * @return ImportFieldMap|null
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function getMappingInfoForInput(string $input): ?ImportFieldMap
    {
        foreach ($this->mapping() as $mapped_field) {
            if ($mapped_field->sourceProperty() === $input) {
                return $mapped_field;
            }
        }
        return null;
    }


    /**
     * Gets the mapping info for the specified model field (eg EEM_Attendee's `ATT_fname` field).
     * Or null if the field wasn't mapped.
     *
     * @param $field_name
     * @return ImportFieldMap|null
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function getMappingInfoForField($field_name): ?ImportFieldMap
    {
        foreach ($this->mapping() as $mapped_field) {
            if ($mapped_field->destinationFieldName() === $field_name) {
                return $mapped_field;
            }
        }
        return null;
    }


    /**
     * @return stdClass
     * @throws EE_Error
     * @throws EE_Error
     * @since 1.0.0.p
     */
    public function toJsonSerializableData(): stdClass
    {
        $simple_obj             = new stdClass();
        $simple_obj->model_name = $this->getModelName();
        $simple_obj->class_name = get_class($this);
        $simple_obj->mapping    = [];
        foreach ($this->mapping() as $mapping_obj) {
            $simple_obj->mapping[ $mapping_obj->destinationFieldName() ] = $mapping_obj->toJsonSerializableData();
        }
        return $simple_obj;
    }


    /**
     * @param $data
     * @return void
     * @throws EE_Error
     */
    public function fromJsonSerializedData($data)
    {
        if ($data instanceof stdClass) {
            if (
                property_exists($data, 'mapping')
                && $data->mapping instanceof stdClass
            ) {
                foreach ($this->fieldNamesMapped() as $field_name) {
                    $field_map = $this->mapping->get($field_name);
                    if (! $field_map instanceof ImportFieldMap) {
                        $field     = $this->getModel()->field_settings_for($field_name);
                        $field_map = LoaderFactory::getLoader()->getNew(
                            'EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap',
                            [
                                null,
                                $field,
                            ]
                        );
                        $this->mapping->add(
                            $field_map,
                            $field_name
                        );
                    }

                    $field_map->fromJsonSerializedData($data->mapping->{$field_name});
                }
                // Don't overwrite the mapping we set.
                $this->initialized = true;
            }
        }
    }
}
// End of file ImportModelConfigBase.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportModelConfigBase.php
