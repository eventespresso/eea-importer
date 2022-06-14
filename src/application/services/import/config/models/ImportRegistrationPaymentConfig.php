<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Base;
use EEM_Registration_Payment;
use ReflectionException;

/**
 * Class ImportRegistrationPaymentConfig
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportRegistrationPaymentConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Registration_Payment|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Registration_Payment::instance();
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
// End of file ImportRegistrationPaymentConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportRegistrationPaymentConfig.php
