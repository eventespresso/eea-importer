<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Attendee;
use EEM_Base;
use ReflectionException;

/**
 * Class ImportAttendeeConfig
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportAttendeeConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Attendee|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Attendee::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     *
     * @return string[]
     * @since 1.0.0.p
     */
    public function fieldNamesMapped(): array
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
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportAttendeeConfig.php
