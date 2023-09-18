<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Error;
use EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigBase;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionLoaderException;
use InvalidArgumentException;

/**
 * Class ImportSingleModelBase
 *
 * Base configuration for when we're importing a single model object from the input data.
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportSingleModelBase extends ImportBaseCommand
{
    /**
     * @var ImportModelConfigBase
     */
    private $config;


    public function __construct(
        array $input_data,
        ImportModelConfigBase $config
    ) {
        parent::__construct($input_data);
        $this->config = $config;
    }


    /**
     * Gets an array where keys are model field names, and values are their coerced values from the input data.
     *
     * @return array
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     * @throws EE_Error
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     * @since 1.0.0.p
     */
    public function getFieldsFromMappedData(): array
    {
        $fields_mapped = $this->config->mapping();
        $fields        = [];
        foreach ($fields_mapped as $field_mapped) {
            // Grab the value from the CSV
            $input_value = $this->valueFromInput(
                $field_mapped->sourceProperty()
            );
            // Skip if we don't have a CSV value to set
            if (is_null($input_value)) {
                continue;
            }
            // Apply mappying to the CSV value:
            // Country -> CNT_ISO
            // State -> STA_ID
            $input_datum = $field_mapped->applyMap(
                $input_value
            );
            // If the CSV value didn't map to anything, skip.
            if (is_null($input_datum)) {
                continue;
            }
            /* @var $field_mapped ImportFieldMap */
            $fields[ $field_mapped->destinationFieldName() ] = $input_datum;
        }
        return $fields;
    }
}
// End of file ImportSingleModelBase.php
// Location: EventEspresso\AttendeeImporter\domain\services\commands/ImportSingleModelBase.php
