<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers;

use DomainException;
use EE_Registry;
use EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;

/**
 * Class ImportCsvAttendeesStep
 *
 * Base class for importing attendees from a CSV file.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
abstract class ImportCsvAttendeesStep extends SequentialStepForm
{
    /**
     * @var ImportCsvAttendeesConfig
     */
    protected $config;

    /**
     * UploadCsv constructor
     *
     * @param $order
     * @param $name
     * @param $longer_name
     * @param $slug
     * @param $form_action
     * @param $form_config
     * @param EE_Registry $registry
     * @param ImportCsvAttendeesConfig $config
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     */
    public function __construct($order, $name, $longer_name, $slug, $form_action, $form_config, EE_Registry $registry, ImportCsvAttendeesConfig $config)
    {
        $this->config = $config;
        parent::__construct(
            $order,
            $name,
            $longer_name,
            $slug,
            $form_action,
            $form_config,
            $registry
        );
    }

}
// End of file ImportCsvAttendeesStep.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\form_handlers/ImportCsvAttendeesStep.php
