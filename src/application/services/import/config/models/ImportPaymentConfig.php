<?php

namespace EventEspresso\AttendeeImporter\application\services\import\config\models;

use EE_Model_Field_Base;
use EEM_Attendee;
use EEM_Base;
use EEM_Payment;
use EventEspresso\AttendeeImporter\application\services\import\config\ImportModelConfigInterface;
use EventEspresso\AttendeeImporter\application\services\import\mapping\ImportMappingCollection;

/**
 * Class ImportPaymentConfig
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportPaymentConfig extends ImportModelConfigBase
{


    /**
     * Gets the model this configuration is for
     * @since $VID:$
     * @return EEM_Base
     */
    public function getModel()
    {
        return EEM_Payment::instance();
    }


    /**
     * Gets the names of the fields on this model that are mapped.
     * @since $VID:$
     * @return string[]
     */
    public function fieldNamesMapped()
    {
        return [
            'PAY_amount',
//            'PMD_ID'
        ];
    }
}
// End of file ImportPaymentConfig.php
// Location: EventEspresso\AttendeeImporter\application\services\import\config\models/ImportPaymentConfig.php
