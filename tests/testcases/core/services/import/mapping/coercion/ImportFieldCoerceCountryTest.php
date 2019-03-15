<?php

namespace EventEspresso\AttendeeImporter\core\services\import\mapping\coercion;

use EEM_Country;
use EventEspresso\core\services\loaders\LoaderFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ImportFieldCoerceStateTest
 *
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportFieldCoerceCountryTest extends PHPUnit_Framework_TestCase
{

    public function countries()
    {
        return [
            'bad_iso' => [
                'ZP', false // where is that?
            ],
            'bad_iso3' => [
                'ZPX', false
            ],
            'bad_name' => [
                'Zapooxy', false // is not a state! It's a country!
            ],
            'good_iso' => [
                'CA', true
            ],
            'good_iso3' => [
                'CAN', true
            ],
            'good_name' => [
                'Canada', true
            ]
        ];
    }

    /**
     * @dataProvider countries
     * @since $VID:$
     */
    public function testCoerce($input, $exists)
    {
        $coercer = LoaderFactory::getLoader()->getShared('EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoerceCountry');
        $this->assertEquals($exists, (bool) $coercer->coerce($input));
    }

    public function testCoerceGetsRightId()
    {
        $coercer = LoaderFactory::getLoader()->getShared('EventEspresso\AttendeeImporter\core\services\import\mapping\coercion\ImportFieldCoerceCountry');
        $a_country = EEM_Country::instance()->get_one();
        $this->assertEquals(
            $a_country->ID(),
            $coercer->coerce($a_country->name())
        );
    }
}
// End of file ImportFieldCoerceStateTest.php
// Location: EventEspresso\AttendeeImporter\core\services\import\mapping\coercion/ImportFieldCoerceStateTest.php
