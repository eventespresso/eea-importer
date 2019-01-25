<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\commands;

use DomainException;
use EE_Attendee;
use EE_Attendee_Importer_Config;
use EE_Error;
use EEM_Attendee;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\commands\CommandHandler;
use EventEspresso\core\services\commands\CommandInterface;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class CreateAttendeeCommandHandler
 * generates and validates an Attendee
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 */
class AttendeeFromCsvRowCommandHandler extends CommandHandler
{
    /**
     * @var EE_Attendee_Importer_Config
     */
    protected $importer_config;

    /**
     */
    public function __construct(EE_Attendee_Importer_Config $config)
    {
        $this->importer_config = $config;
    }


    /**
     * @param CommandInterface $command
     * @return EE_Attendee
     * @throws EE_Error
     * @throws InvalidEntityException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws ReflectionException
     */
    public function handle(CommandInterface $command)
    {
        /** @var AttendeeFromCsvRowCommand $command */
        if (! $command instanceof AttendeeFromCsvRowCommand) {
            throw new InvalidEntityException(
                get_class($command),
                'EventEspresso\AttendeeImporter\core\domain\services\commands\AttendeeFromCsvRowCommand'
            );
        }
        // Find columns that correspond to the Attendee model.
        $fields_to_columns = $this->importer_config->getCsvColumnsForModel(EEM_Attendee::instance());
        if (!isset($fields_to_columns['ATT_fname'], $fields_to_columns['ATT_email'])) {
            throw new DomainException(
                esc_html__(
                // @codingStandardsIgnoreStart
                    'You must at least specify the attendee’s firstname and email address in order to import.',
                    // @codingStandardsIgnoreEnd
                    'event_espresso'
                )
            );
        }
        $fields_to_values = [];
        $row = $command->csvRow();
        foreach($fields_to_columns as $field_name => $csv_column_name) {
            $fields_to_values[$field_name] = $row[$csv_column_name];
        }
        // Check for a duplicate attendee in the DB already. If it exists, return it.
        $attendee = EEM_Attendee::instance()->find_existing_attendee(
            array_intersect_key(
                $fields_to_values,
                array_flip(
                    [
                        'ATT_fname',
                        'ATT_lname',
                        'ATT_email'
                    ]
                )
            )
        );
        if (! $attendee) {
            // If none exists, create a new attendee
            $attendee = EE_Attendee::new_instance(
                [
                    'ATT_author' => get_current_user_id()
                ]
            );
        }
        $attendee->save(
            $fields_to_values
        );
        return $attendee;
    }
}
