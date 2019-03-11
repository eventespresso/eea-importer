<?php

namespace EventEspresso\AttendeeImporter\core\services\import\config\models;
use EE_Model_Field_Base;
use EEM_Base;

/**
 * Class ImportRegistrationConfig
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportRegistrationConfig extends ImportModelConfigBase
{


    /**
     * Gets the model this configuration is for
     * @since $VID:$
     * @return EEM_Base
     */
    public function getModel()
    {
        return \EEM_Registration::instance();
    }
}
// End of file ImportRegistrationConfig.php
// Location: EventEspresso\AttendeeImporter\core\services\import\config\models/ImportRegistrationConfig.php
