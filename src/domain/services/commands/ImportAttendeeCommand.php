<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Registration;

/**
 * Class CreateAttendeeCommand
 * DTO for passing data to a ImportAttendeeCommandHandler
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 */
class ImportAttendeeCommand extends ImportBaseCommand
{

    /**
     * @var EE_Registration
     */
    private $registration;

    public function __construct(EE_Registration $reg, array $csv_row)
    {
        parent::__construct($csv_row);
        $this->registration = $reg;
    }

    /**
     * @return EE_Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }
}
