<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Registration;
use EventEspresso\core\domain\services\capabilities\CapCheck;
use EventEspresso\core\domain\services\capabilities\CapCheckInterface;
use EventEspresso\core\domain\services\capabilities\PublicCapabilities;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\services\commands\Command;
use EventEspresso\core\services\commands\CommandRequiresCapCheckInterface;

/**
 * Class CreateAttendeeCommand
 * DTO for passing data to a AnswersFromCsvRowCommandHandler
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 */
class ImportAnswersCommand extends Command implements CommandRequiresCapCheckInterface
{

    /**
     * array of details where keys are names of EEM_Attendee model fields
     *
     * @var array $csv_row
     */
    protected $csv_row;

    /**
     * an existing registration to associate this attendee with
     *
     * @var EE_Registration $registration
     */
    protected $registration;


    /**
     * CreateAttendeeCommand constructor.
     *
     * @param array           $csv_row
     * @param EE_Registration $config
     */
    public function __construct(array $csv_row, \EE_Attendee_Importer_Config $config)
    {
        $this->csv_row = $csv_row;
        $this->registration = $config;
    }


    /**
     * @return CapCheckInterface
     * @throws InvalidDataTypeException
     */
    public function getCapCheck()
    {
        return new CapCheck('import', 'ee_attendee_import');
    }
}
