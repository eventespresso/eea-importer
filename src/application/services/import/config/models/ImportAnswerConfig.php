<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Error;
use EEM_Answer;
use EEM_Base;
use ReflectionException;

/**
 * Class ImportAnswerConfig
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class ImportAnswerConfig extends ImportModelConfigBase
{
    /**
     * Gets the model this configuration is for
     *
     * @return EEM_Answer|EEM_Base
     * @throws EE_Error
     * @throws ReflectionException
     * @since 1.0.0.p
     */
    public function getModel(): EEM_Base
    {
        return EEM_Answer::instance();
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
            'QST_ID',
            'ANS_ID',
            'ANS_value',
        ];
    }
}
// End of file ImportAnswerConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportAnswerConfig.php
