<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Base;
use EEM_Payment;
use ReflectionException;

/**
 * Class ImportPaymentConfig
 *
 * Configuration saying how to import a payment from source data.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         1.0.0.p
 *
 */
class ImportPaymentConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Payment|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Payment::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     * @since 1.0.0.p
     * @return string[]
     */
    public function fieldNamesMapped(): array
    {
        return [
            'PAY_amount',
        ];
    }
}
// End of file ImportPaymentConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportPaymentConfig.php
