<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;
use EE_Model_Field_Base;
use EEM_Attendee;
use EEM_Base;
use EventEspresso\AttendeeImporter\core\services\import\config\ImportModelConfigInterface;

/**
 * Class ImportAttendeeConfig
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportAttendeeConfig extends ImportModelConfigBase
{


    /**
     * Gets the model this configuration is for
     * @since $VID:$
     * @return EEM_Base
     */
    public function getModel()
    {
        return EEM_Attendee::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     * @since $VID:$
     * @return string[]
     */
    public function fieldNamesMapped()
    {
        return [
            'ATT_fname',
            'ATT_lname',
            'ATT_email',
            'ATT_address',
            'ATT_address2',
            'ATT_city',
            'STA_ID',
            'CNT_ISO',
            'ATT_zip',
            'ATT_phone',
        ];
    }
}
// End of file ImportAttendeeConfig.php
// Location: EventEspresso\AttendeeImporter\core\services\import\config\models/ImportAttendeeConfig.php
