<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Base;
use EEM_Registration;
use ReflectionException;

/**
 * Class ImportRegistrationConfig
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportRegistrationConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Registration|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Registration::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     *
     * @return string[]
     * @since 1.0.0.p
     */
    public function fieldNamesMapped(): array
    {
        return [];
    }
}
// End of file ImportRegistrationConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportRegistrationConfig.php
