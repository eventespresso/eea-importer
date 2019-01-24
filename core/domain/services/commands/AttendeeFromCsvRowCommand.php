<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\commands;

use EE_Attendee_Importer_Config;
use EE_Registration;
use EventEspresso\core\domain\services\capabilities\CapCheck;
use EventEspresso\core\domain\services\capabilities\CapCheckInterface;
use EventEspresso\core\domain\services\capabilities\PublicCapabilities;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\services\commands\Command;
use EventEspresso\core\services\commands\CommandRequiresCapCheckInterface;

/**
 * Class CreateAttendeeCommand
 * DTO for passing data to a AttendeeFromCsvRowCommandHandler
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 */
class AttendeeFromCsvRowCommand extends Command implements CommandRequiresCapCheckInterface
{

    /**
     * @var array $csv_row
     */
    protected $csv_row;


    /**
     * CreateAttendeeCommand constructor.
     *
     * @param array           $csv_row
     * @param EE_Registration $config
     */
    public function __construct(array $csv_row)
    {
        $this->csv_row = $csv_row;
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
