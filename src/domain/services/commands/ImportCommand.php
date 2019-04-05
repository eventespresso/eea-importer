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
        $this->config = $config;
        $this->option_manager = $option_manager;
    }

    /**
     * Takes care of always populating it from the DB on first call.
     * @since $VID:$
     * @return ImportCsvAttendeesConfig
     */
    public function getConfig()
    {
        if (! $this->config_populated_from_db) {
            $this->option_manager->populateFromDb($this->config);
            $this->config_populated_from_db = true;
        }
        return $this->config;
    }
}
