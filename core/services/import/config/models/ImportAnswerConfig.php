<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;

use EE_Model_Field_Base;
use EEM_Attendee;
use EEM_Base;
use EventEspresso\AttendeeImporter\core\services\import\config\ImportModelConfigInterface;
use EventEspresso\AttendeeImporter\services\import\mapping\ImportMappingCollection;

/**
 * Class ImportAnswerConfig
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportAnswerConfig extends ImportModelConfigBase
{


    /**
     * Gets the model this configuration is for
     * @since $VID:$
     * @return EEM_Base
     */
    public function getModel()
    {
        return EEM_Answer::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     * @since $VID:$
     * @return string[]
     */
    public function fieldNamesMapped()
    {
        return [
            'QST_ID',
            'ANS_ID',
            'ANS_value'
        ];
    }
}
// End of file ImportAnswerConfig.php
// Location: EventEspresso\AttendeeImporter\core\services\import\config\models/ImportAnswerConfig.php
