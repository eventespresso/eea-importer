<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Base;
use EEM_Transaction;
use ReflectionException;

/**
 * Class ImportTransactionConfig
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportTransactionConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Transaction|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Transaction::instance();
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
// End of file ImportTransactionConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportTransactionConfig.php
