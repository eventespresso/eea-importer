<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\core\services\options\JsonWpOptionManager;

/**
 * Class CreateAttendeeCommand
 * DTO for passing data to a ImportCommandHandler
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 */
class ImportCommand extends ImportBaseCommand
{
    /**
     * @var ImportCsvAttendeesConfig
     */
    private $config;

    /**
     * @var bool
     */
    private $config_populated_from_db = false;

    /**
     * @var JsonWpOptionManager
     */
    private $option_manager;


    public function __construct(
        array $input_data,
        ImportCsvAttendeesConfig $config,
        JsonWpOptionManager $option_manager
    ) {
        parent::__construct($input_data);
        $this->config         = $config;
        $this->option_manager = $option_manager;
    }


    /**
     * Takes care of always populating it from the DB on first call.
     *
     * @return ImportCsvAttendeesConfig
     * @since 1.0.0.p
     */
    public function getConfig(): ImportCsvAttendeesConfig
    {
        if (! $this->config_populated_from_db) {
            $this->option_manager->populateFromDb($this->config);
            $this->config_populated_from_db = true;
        }
        return $this->config;
    }


    /**
     * Checks if absolutely all data-points are blank. If so, returns true. Otherwise, returns false.
     *
     * @return bool
     * @since 1.0.0.p
     */
    public function rowIsOnlyBlanks(): bool
    {
        foreach ($this->inputData() as $value) {
            if (is_string($value)) {
                $value = trim($value);
            }
            if ($value !== null && $value !== '') {
                return false;
            }
        }
        return true;
    }
}
