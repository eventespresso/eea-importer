<?php

namespace EventEspresso\AttendeeImporter\application\services\import\extractors;

/**
 * Class Extractor
 *
 * Interface for extractors. Extractors grab and count data from source.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
interface ImportExtractorInterface
{
    /**
     * Sets what source to extract data from. Eg filepath of uploaded CSV, database table names, request parameter, etc.
     * It's up to the extractor to interpret what was provided in order to get items.
     *
     * @param string $source
     * @return void
     * @since 1.0.0.p
     */
    public function setSource(string $source);


    /**
     * Gets an array of the raw data from the source (eg a row from the CSV, a JSON object) at the specified index.
     *
     * @param $offset
     * @return array|null
     * @since 1.0.0.p
     */
    public function getItemAt($offset): ?array;


    /**
     * Gets the next item after the previously returned item.
     *
     * @return array|null if all done.
     * @since 1.0.0.p
     */
    public function getNextItem(): ?array;


    /**
     * Counts the number of items to import
     *
     * @return int
     * @since 1.0.0.p
     */
    public function countItems(): int;
}
// End of file Extractor.php
// Location: ${NAMESPACE}/Extractor.php
