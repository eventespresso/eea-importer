<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config;

use EventEspresso\AttendeeImporter\core\services\import\config\ImportConfigBase;
use EventEspresso\AttendeeImporter\core\services\import\config\ImportModelConfigInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\loaders\LoaderFactory;
use InvalidArgumentException;
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
     * @return mixed
     */
    public function getModelNamesImported()
    {
        return [
            'Attendee',
            'Registration',
            'Transaction',
            'Payment',
            'Answer'
        ];
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
        if($data instanceof stdClass
        && property_exists($data, 'file')){
            $this->file = $data->file;
            return true;
        }
        return false;
    }
}
// End of file ImportCsvAttendeesConfig.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config/ImportCsvAttendeesConfig.php
