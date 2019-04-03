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
 * Class ImportAnswersCommand
 * DTO for passing data to a ImportAnswersCommandHandler
 *
 * @package       Event Espresso
 * @author        Michael Nelson
 */
class ImportAnswersCommand extends ImportBaseCommand
{

    /**
     * an existing registration to associate this attendee with
     *
     * @var EE_Registration $registration
     */
    protected $registration;


    /**
     * CreateAttendeeCommand constructor.
     *
     * @param EE_Registration $registration
     * @param array $csv_row
     */
    public function __construct(EE_Registration $registration, array $csv_row)
    {
        $this->registration = $registration;
        parent::__construct($csv_row);
    }

    /**
     * @return EE_Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }
}
