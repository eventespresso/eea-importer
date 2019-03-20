<?php

namespace EventEspresso\AttendeeImporter\core\services\import\extractors;

use EventEspresso\core\exceptions\InvalidFilePathException;
use LogicException;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Class ImportExtractorCsvTest
 *
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class ImportExtractorCsvTest extends PHPUnit_Framework_TestCase
{
    /**
     * @since $VID:$
     * @throws InvalidFilePathException
     * @throws LogicException
     * @throws \RuntimeException
     * @expectedException RuntimeException
     */
    public function testSetSourceInvalidFilepath()
    {
        $extractor = new ImportExtractorCsv();
        $extractor->setSource('');
    }

    public function testCountEmpty()
    {
        $extractor = $this->extractorForTestCsv('empty.csv');
        // Technically there is one line in the CSV file, even though it's empty. It's an empty line with 1 column.
        $this->assertEquals(1,$extractor->countItems());
    }

    public function testGetItemAtForEmptyFile()
    {
        $extractor = $this->extractorForTestCsv('empty.csv');
        $headers = $extractor->getItemAt(0);
        // Technically there is one column, which is empty.
        $this->assertEquals([''],$headers);
    }

    public function testCount()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $this->assertEquals(4,$extractor->countItems());
    }

    public function testGetItemAtTop()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $headers = $extractor->getItemAt(0);
        $this->assertEquals('Column 1', $headers[0]);
        $this->assertEquals('Column 2', $headers[1]);
        $this->assertEquals('Column 3', $headers[2]);
    }

    public function testGetItemAtMiddle()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $headers = $extractor->getItemAt(1);
        $this->assertEquals('1', $headers[0]);
        $this->assertEquals('A', $headers[1]);
        $this->assertEquals('a', $headers[2]);
    }

    public function testGetItemAtPenultimateLine()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $headers = $extractor->getItemAt(2);
        $this->assertEquals('2', $headers[0]);
        $this->assertEquals('B', $headers[1]);
        $this->assertEquals('b', $headers[2]);
    }

    public function testGetItemAtLastLine()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $headers = $extractor->getItemAt(3);
        $this->assertEquals('3', $headers[0]);
        $this->assertEquals('C', $headers[1]);
        $this->assertEquals('c', $headers[2]);
    }

    public function testGetItemAfterLast()
    {
        $extractor = $this->extractorForTestCsv('test.csv');
        $this->assertEquals(null, $extractor->getItemAt(4));
    }

    /**
     * @since $VID:$
     * @param string $test_csv_filename name of the file in attendee importer test csvs folder.
     * @return ImportExtractorCsv
     * @throws RuntimeException
     * @throws InvalidFilePathException
     * @throws LogicException
     */
    protected function extractorForTestCsv( $test_csv_filename)
    {
        $extractor = new ImportExtractorCsv();
        $extractor->setSource(EE_ATTENDEE_IMPORTER_TEST_CSVS_DIR . $test_csv_filename);
        return $extractor;
    }
}
// End of file ImportExtractorCsvTest.php
// Location: EventEspresso\AttendeeImporter\core\services\import\extractors/ImportExtractorCsvTest.php
