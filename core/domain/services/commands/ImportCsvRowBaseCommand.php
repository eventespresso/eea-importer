<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\commands;
use EventEspresso\core\domain\services\capabilities\CapCheck;
use EventEspresso\core\domain\services\capabilities\CapCheckInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\services\commands\Command;
use EventEspresso\core\services\commands\CommandRequiresCapCheckInterface;

/**
 * Class ModelObjFromCsvRowCommand
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportCsvRowBaseCommand  extends Command implements CommandRequiresCapCheckInterface
{
    /**
     * @var array $csv_row
     */
    protected $csv_row;


    /**
     * CreateAttendeeCommand constructor.
     *
     * @param array           $csv_row
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

    /**
     * Returns the array from the CSV row, where keys are CSV columsn names, values are their values.
     * @since $VID:$
     * @return array
     */
    public function csvRow()
    {
        return $this->csv_row;
    }
}
// End of file ModelObjFromCsvRowCommand.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\commands/ModelObjFromCsvRowCommand.php
