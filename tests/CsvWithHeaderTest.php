<?php

use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\data\Csv;
use umbalaconmeogia\phputil\data\CsvWithHeader;

class CsvWithHeaderTest extends TestCase
{
    private $csvFile = __DIR__ . "/fixtures/people.csv";

    /**
     * @var array
     */
    private $fixtureData;

    /**
     * @var CsvWithHeader
     */
    private $csvWithHeader;

    protected function setUp(): void
    {
        $this->fixtureData = Csv::csvToArray($this->csvFile);
    }

    protected function tearDown(): void
    {
        if ($this->csvWithHeader) {
            $this->csvWithHeader->fclose();
        }
    }

    private function openCsvWithHeader(): void
    {
        $this->csvWithHeader = new CsvWithHeader();
        $this->csvWithHeader->fopen($this->csvFile);
    }


    public function testLoadRow()
    {
        $this->openCsvWithHeader();
        $row = $this->csvWithHeader->loadRow();

        $this->assertEquals($this->fixtureData[0], $row);
    }

    public function testSkipRow()
    {
        $this->openCsvWithHeader();

        $row = $this->csvWithHeader->loadRow(); // Row 0
        $this->csvWithHeader->skipRow(); // Row 1
        $row = $this->csvWithHeader->loadRow(); // Row 2

        $this->assertEquals($this->fixtureData[2], $row);
    }


    public function testSkipSeveralRow()
    {
        $this->openCsvWithHeader();

        $this->csvWithHeader->skipRow(2); // Row 1
        $row = $this->csvWithHeader->loadRow(); // Row 2

        $this->assertEquals($this->fixtureData[2], $row);
    }

    public function testGetRowAttributes()
    {
        $this->openCsvWithHeader();
        $this->csvWithHeader->loadHeader();
        $this->csvWithHeader->loadRow();
        $attr = $this->csvWithHeader->getRowAsAttributes();

        $this->assertEquals($this->fixtureData[0], $this->csvWithHeader->getHeader());
        $firstRowIndex = 1;
        $this->assertEquals($this->fixtureData[$firstRowIndex][0], $attr['name']);
        $this->assertEquals($this->fixtureData[$firstRowIndex][1], $attr['age']);
        $this->assertEquals($this->fixtureData[$firstRowIndex][2], $attr['address']);
    }

    public function testReadMostUsagePattern()
    {
        CsvWithHeader::read($this->csvFile, function(CsvWithHeader $csv) {

            $this->assertEquals($this->fixtureData[0], $csv->getHeader());

            $row = 0;
            while ($csv->loadRow() !== FALSE) {
                $row++;
                $attr = $csv->getRowAsAttributes();
                foreach ($csv->getHeader() as $col => $header) {
                    $this->assertEquals($this->fixtureData[$row][$col], $attr[$header]);
                }
            }
        });
    }
}