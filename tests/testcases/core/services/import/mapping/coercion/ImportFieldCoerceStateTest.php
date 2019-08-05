<?php

namespace EventEspresso\AttendeeImporter\application\services\import\mapping\coercion;

use EventEspresso\core\services\loaders\LoaderFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ImportFieldCoerceStateTest
 *
 * Description
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         1.0.0.p
 *
 */
class ImportFieldCoerceStateTest extends PHPUnit_Framework_TestCase
{

    public function states()
    {
        return [
            'bad_abbrev' => [
                'CAN', false // is not a state! It's a country!
            ],
            'bad_id' => [
                100000, false
            ],
            'bad_name' => [
                'Canada', false // is not a state! It's a country!
            ],
            'good_abbrev' => [
                'CA', true
            ],
            'good_id' => [
                1, true
            ],
            'good_name' => [
                'California', true
            ]
        ];
    }

    /**
     * @dataProvider states
     * @since 1.0.0.p
     */
    public function testCoerce($input, $exists)
    {
        $coercer = LoaderFactory::getLoader()->getShared('EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoerceState');
        $this->assertEquals($exists, (bool) $coercer->coerce($input));
    }

    public function testCoerceGetsRightId()
    {
        $coercer = LoaderFactory::getLoader()->getShared('EventEspresso\AttendeeImporter\application\services\import\mapping\coercion\ImportFieldCoerceState');
        $a_state = \EEM_State::instance()->get_one();
        $this->assertEquals(
            $a_state->ID(),
            $coercer->coerce($a_state->abbrev())
        );
    }

}
// End of file ImportFieldCoerceStateTest.php
// Location: EventEspresso\AttendeeImporter\application\services\import\mapping\coercion/ImportFieldCoerceStateTest.php
