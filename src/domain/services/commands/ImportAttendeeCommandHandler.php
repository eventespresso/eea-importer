<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use EE_Attendee;
use EE_Attendee_Importer_Config;
use EE_Error;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\commands\CommandInterface;
use EventEspresso\core\services\commands\CompositeCommandHandler;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class CreateAttendeeCommandHandler
 * Creates an attendee from an unsaved registration.
 *
 * @package       Event Espresso
 * @author        Michal Nelson
 */
class ImportAttendeeCommandHandler extends CompositeCommandHandler
{

    /**
     * @param CommandInterface|ImportAttendeeCommand $command
     * @return EE_Attendee
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidEntityException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    public function handle(CommandInterface $command)
    {
        // Create an attendee

        $attendee = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\attendee\CreateAttendeeCommand',
                [
                    $command->getFieldsFromMappedData(),
                    $command->getRegistration()
                ]
            )
        );
        return $attendee;
    }
}
