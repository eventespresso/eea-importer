<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;
use EE_Error;
use EE_Model_Field_Base;
use EEM_Base;
use EventEspresso\AttendeeImporter\core\services\import\mapping\ImportFieldMap;
use EventEspresso\core\services\collections\CollectionDetails;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\collections\CollectionLoader;
use EventEspresso\core\services\collections\CollectionLoaderException;
use stdClass;

/**
 * Class ImportModelConfigBase
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
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

    public function __construct()
    {
        // Create the collection from the fields mapped.
        $loader = new CollectionLoader(
            new CollectionDetails(
            // collection name
                'import_csv_attendees_config_model_configs_field_mapping',
                // collection interface
                'EventEspresso\AttendeeImporter\core\services\import\mapping\ImportFieldMap',
                // FQCNs for classes to add (all classes within that namespace will be loaded)
                [],
                [],
                '',
                CollectionDetails::ID_CALLBACK_METHOD,
                'destinationFieldName'
            )
        );
        $this->mapping = $loader->getCollection();
    }

    /**
     * @since $VID:$
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     */
    protected function checkInitialized()
    {
        if (! $this->initialized) {
            $this->init();
        }
    }
    /**
     * Returns the list of model fields that can be imported for this model.
     * @since $VID:$
     * @return void
     * @throws EE_Error
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     */
    public function init()
    {
        foreach ($this->fieldNamesMapped() as $field_name) {
            $field = $this->getModel()->field_settings_for($field_name);
            $this->mapping->add(
                new ImportFieldMap(
                    $field
                ),
                $field_name
            );
        }
        $this->initialized = true;
    }

    /**
     * Gets a collection that states how this import fields should be mapped to EE model fields for this model.
     * @since $VID:$
     * @return CollectionInterface|ImportFieldMap[]
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     */
    public function mapping()
    {
        $this->checkInitialized();
        return $this->mapping;
    }


    /**
     * Shortcut to get the name of the model this affects.
     * @since $VID:$
     * @return string
     */
    public function getModelName()
    {
        return $this->getModel()->get_this_model_name();
    }
    
    public function map($input_column, $field_name)
    {
        $map_obj = $this->mapping->get($field_name);
        $map_obj->map($input_column);
    }

    /**
     * Gets the mapping info for the specified input (eg a CSV column name),
     * or null if the input source property isn't mapped.
     * @since $VID:$
     * @param string $input
     * @return ImportFieldMap|null
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     */
    public function getMappingInfoForInput($input)
    {
        foreach($this->mapping() as $mapped_field){
            if ($mapped_field->sourceProperty() === $input) {
                return $mapped_field;
            }
        }
        return null;
    }

    /**
     * @since $VID:$
     * @return mixed|void
     */
    public function toJsonSerializableData()
    {
        $simple_obj = new stdClass();
        $simple_obj->model_name = $this->getModelName();
        $simple_obj->class_name = get_class($this);
        $simple_obj->mapping = [];
        foreach($this->mapping() as $mapping_obj) {
            $simple_obj->mapping[$mapping_obj->destinationFieldName()] = $mapping_obj->toJsonSerializableData();
        }
        return $simple_obj;
    }

    public function fromJsonSerializedData($data)
    {
        if($data instanceof stdClass) {
            if( property_exists($data, 'mapping')
            && $data->mapping instanceof stdClass){
                foreach($data->mapping as $json_key => $json_value) {
                    $field = $this->getModel()->field_settings_for($json_key);
                    $field_mapping = new ImportFieldMap($field);
                    $field_mapping->fromJsonSerializedData($json_value);
                    $this->mapping->add($field_mapping, $json_key);
                }
                // Don't overwrite the mapping we set.
                $this->initialized = true;
            }
        }
    }
}
// End of file ImportModelConfigBase.php
// Location: EventEspresso\AttendeeImporter\core\services\import\config\models/ImportModelConfigBase.php
