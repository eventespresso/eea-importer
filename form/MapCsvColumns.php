<?php

namespace EventEspresso\AttendeeImporter\form;
use DomainException;
use EE_Form_Section_Proper;
use EE_Registry;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\core\libraries\form_sections\form_handlers\SequentialStepForm;
use InvalidArgumentException;

/**
 * Class MapCsvColumns
 *
 * Step for uploading the CSV file to import.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class MapCsvColumns extends SequentialStepForm
{

    /**
     * MapCsvColumns constructor
     *
     * @param EE_Registry $registry
     * @throws DomainException
     * @throws InvalidDataTypeException
     * @throws InvalidArgumentException
     */
    public function __construct(EE_Registry $registry)
    {
        $this->setDisplayable(true);
        parent::__construct(
            2,
            esc_html__('Map CSV Columns To Event Espresso Data', 'event_espresso'),
            esc_html__('"Map CSV Columns to Event Espresso Data" Attendee Importer Step', 'event_espresso'),
            'map',
            '',
            FormHandler::ADD_FORM_TAGS_AND_SUBMIT,
            $registry
        );
    }


    /**
     * creates and returns the actual form
     *
     * @return EE_Form_Section_Proper
     */
    public function generate()
    {
        return new EE_Form_Section_Proper(
            [
                'name' => 'map'
            ]
        );
    }
}
// End of file MapCsvColumns.php
// Location: EventEspresso\AttendeeImporter\form/MapCsvColumns.php
