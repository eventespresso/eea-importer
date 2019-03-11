<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config;

use EventEspresso\AttendeeImporter\core\services\import\config\ImportConfigBase;
use EventEspresso\AttendeeImporter\core\services\import\config\ImportModelConfigInterface;
use EventEspresso\core\services\collections\CollectionDetails;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionInterface;
use EventEspresso\core\services\collections\CollectionLoader;
use EventEspresso\core\services\collections\CollectionLoaderException;
use LogicException;
use RuntimeException;
use SplFileObject;
use stdClass;

/**
 * Class ImportCsvAttendeesConfig
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportCsvAttendeesConfig extends ImportConfigBase
{
    /**
     * @var string
     */
    protected $file;

    /**
     * Gets the filepath to read.
     * @since $VID:$
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the filepath of the CSV file.
     * @since $VID:$
     * @param $file_path
     */
    public function setFile($file_path)
    {
        $this->file = $file_path;
    }

    /**
     * @since $VID:$
     * @return SplFileObject
     * @throws LogicException
     * @throws RuntimeException
     */
    public function getFileHandle()
    {
        return new SplFileObject($this->file, 'r');
    }

    /**
     * Gets the name of the WordPress option where this JSON data will be stored.
     * @since $VID:$
     * @return string
     */
    public function getWpOptionName()
    {
        return 'ee_import_csv_attendees_config';
    }

    /**
     * @since $VID:$
     * @return CollectionInterface|ImportModelConfigInterface[]
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     */
    protected function initializeModelConfigCollection()
    {
        $loader = new CollectionLoader(
            new CollectionDetails(
                // collection name
                'import_csv_attendees_config_model_configs',
                // collection interface
                'EventEspresso\AttendeeImporter\core\services\import\config\models\ImportModelConfigBase',
                // FQCNs for classes to add (all classes within that namespace will be loaded)
                [
                    'EventEspresso\AttendeeImporter\core\services\import\config\models\ImportAttendeeConfig'
                ],
                [],
                '',
                CollectionDetails::ID_CALLBACK_METHOD,
                'getModelName'
            )
        );
        return $loader->getCollection();
    }

    /**
     * Creates a simple PHP array or stdClass from this object's properties, which can be easily serialized using
     * wp_json_serialize().
     * @since $VID:$
     * @return mixed
     */
    public function toJsonSerializableData()
    {
        $simple_obj = parent::toJsonSerializableData();
        $simple_obj->file = $this->file;
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
        parent::fromJsonSerializedData($data);
        if ($data instanceof stdClass
            && property_exists($data, 'file')) {
            $this->file = $data->file;
            return true;
        }
        return false;
    }
}
// End of file ImportCsvAttendeesConfig.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config/ImportCsvAttendeesConfig.php
