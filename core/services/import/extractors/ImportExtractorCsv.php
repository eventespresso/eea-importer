<?php

namespace EventEspresso\AttendeeImporter\core\services\import\extractors;
use EventEspresso\core\exceptions\InvalidFilePathException;
use SplFileObject;

/**
 * Class ImportExtractorCsv
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportExtractorCsv extends ImportExtractorBase
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var SplFileObject
     */
    protected $fileObject;

    /**
     * Sets what source to extract data from. Eg filepath of uploaded CSV, database table names, request parameter, etc.
     * It's up to the extractor to interpret what was provided in order to get items.
     * @since $VID:$
     * @param string $source
     * @return void
     * @throws InvalidFilePathException
     */
    public function setSource($source)
    {
        $this->filepath = (string)$source;

        $this->fileObject = new SplFileObject($this->filepath, 'r');

        if ($this->fileObject->eof()) {
            throw new InvalidFilePathException(
                $source,
                esc_html__('No comma-separated data was retrieved from the CSV file provided.', 'event_espresso')
            );
        }
    }

    /**
     * Gets an array of the raw data from the source (eg a row from the CSV, a JSON object,
     * @since $VID:$
     * @return array
     */
    public function getItemAt($offset)
    {
        $this->fileObject->seek($offset);
        $return_value = $this->fileObject->fgetcsv();
        return $return_value;
    }

    /**
     * Gets the next row.
     * @since $VID:$
     * @return array|null if all done.
     */
    public function getNextItem()
    {
        if ($this->fileObject->eof()) {
            return null;
        }
        return $this->fileObject->fgetcsv();
    }

    /**
     * Counts the number of items to import
     * @since $VID:$
     * @return int
     */
    public function countItems()
    {
        $this->fileObject->seek(PHP_INT_MAX);
        return $this->fileObject->key() - 1;
    }
}
// End of file ImportExtractorCsv.php
// Location: EventEspresso\AttendeeImporter\core\services\import\extractors/ImportExtractorCsv.php
