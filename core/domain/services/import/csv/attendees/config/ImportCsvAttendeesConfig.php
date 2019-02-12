<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config;

use EventEspresso\AttendeeImporter\core\services\import\config\ImportConfigBase;
use EventEspresso\AttendeeImporter\core\services\import\config\ImportModelConfigInterface;
use LogicException;
use RuntimeException;
use SplFileObject;

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
        $this->setDirty();
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
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        $arr = array_merge(
            $arr,
            [
                'file' => $this->file
            ]
        );
        return $arr;
    }


    /**
     * @since $VID:$
     * @param array $data
     */
    public function fromArray(array $data)
    {
        $this->file = $data['file'];
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
}
// End of file ImportCsvAttendeesConfig.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config/ImportCsvAttendeesConfig.php
