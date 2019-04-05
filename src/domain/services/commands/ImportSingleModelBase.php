<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigBase;
use EventEspresso\core\services\collections\CollectionDetailsException;
use EventEspresso\core\services\collections\CollectionLoaderException;

/**
 * Class ImportSingleModelBase
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
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
     * Gets an array where keys are model field names, and values are their coerced values fromthe input data.
     * @since $VID:$
     * @return array
     * @throws \EE_Error
     * @throws CollectionDetailsException
     * @throws CollectionLoaderException
     */
    public function getFieldsFromMappedData()
    {
        $fields_mapped = $this->config->mapping();
        $fields = [];
        foreach ($fields_mapped as $field_mapped) {
            /* @var $field_mapped ImportFieldMap */
            $fields[ $field_mapped->destinationFieldName() ] = $field_mapped->applyMap(
                $this->csvColumnValue(
                    $field_mapped->sourceProperty()
                )
            );
        }
        return $fields;
    }
}
// End of file ImportSingleModelBase.php
// Location: EventEspresso\AttendeeImporter\domain\services\commands/ImportSingleModelBase.php
