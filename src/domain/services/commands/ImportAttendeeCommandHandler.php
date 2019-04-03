<?php

namespace EventEspresso\AttendeeImporter\domain\services\commands;

use DomainException;
use EE_Attendee;
use EE_Attendee_Importer_Config;
use EE_Error;
use EEM_Attendee;
use EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigInterface;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportFieldMap;
use EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\config\ImportCsvAttendeesConfig;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\commands\CommandBusInterface;
use EventEspresso\core\services\commands\CommandFactoryInterface;
use EventEspresso\core\services\commands\CommandHandler;
use EventEspresso\core\services\commands\CommandInterface;
use EventEspresso\core\services\commands\CompositeCommandHandler;
use EventEspresso\core\services\options\JsonWpOptionManager;
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
     * @var EE_Attendee_Importer_Config
     */
    protected $importer_config;
    /**
     * @var ImportCsvAttendeesConfig
     */
    private $config;

    public function __construct(
        CommandBusInterface $command_bus,
        CommandFactoryInterface $command_factory,
        ImportCsvAttendeesConfig $config
    ) {
        parent::__construct($command_bus, $command_factory);
        $this->config = $config;
    }

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
        $attendee_config = $this->config->getModelConfigs()->get('Attendee');
        if (!$attendee_config instanceof ImportModelConfigInterface) {
            throw new InvalidEntityException(
                $attendee_config,
                'EventEspresso\AttendeeImporter\application\services\import\config\models\ImportModelConfigInterface'
            );
        }
        $fields_mapped = $attendee_config->mapping();
        $attendee_fields = [];
        foreach ($fields_mapped as $field_mapped) {
            /* @var $field_mapped ImportFieldMap */
            $attendee_fields[ $field_mapped->destinationFieldName() ] = $field_mapped->applyMap(
                $command->csvColumnValue(
                    $field_mapped->sourceProperty()
                )
            );
        }
        $attendee = $this->commandBus()->execute(
            $this->commandFactory()->getNew(
                'EventEspresso\core\services\commands\attendee\CreateAttendeeCommand',
                [
                    $attendee_fields,
                    $command->getRegistration()
                ]
            )
        );
        return $attendee;
    }
}
