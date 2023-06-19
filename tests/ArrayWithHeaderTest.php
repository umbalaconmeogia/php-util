<?php

use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\data\Csv;
use umbalaconmeogia\phputil\data\ArrayWithHeader;

Csv::correctUtf8CsvOnWindows();

class ArrayWithHeaderTest extends TestCase
{
    private $csvFile = __DIR__ . "/fixtures/people.csv";

    /**
     * @var array
     */
    private $fixtureData;

    private $arrayWH;

    protected function setUp(): void
    {
        $this->fixtureData = Csv::csvToArray($this->csvFile);
        $this->arrayWH = new ArrayWithHeader($this->fixtureData);
    }

    public function testHeader()
    {
        // Test header.
        $expectedHeader = ['name', 'age', 'address'];
        $this->assertEquals($expectedHeader, $this->arrayWH->getHeader());

        // Test attributeIndexes.
        $attributeIndexes = $this->arrayWH->getAttributeIndexes();
        foreach ($expectedHeader as $index => $attribute) {
            $this->assertEquals($attributeIndexes[$attribute], $index);
        }
    }

    public function testGetArrayData()
    {
        $arrayData = $this->arrayWH->getArrayData();
        $this->assertEquals(3, count($arrayData));
    }

    public function testGetRow()
    {
        $rows = [
            1 => [0 => 'Nguyen Van A', 2 => "Ho Chi Minh city, Vietnam"],
            2 => [0 => '山田 太郎', 2 => '東京都、日本'],
        ];
        foreach ($rows as $rowIndex => $row) {
            $rowWH = $this->arrayWH->getRow($rowIndex);
            $this->assertEquals($rows[$rowIndex][0], $rowWH[0]);
            $this->assertEquals($rows[$rowIndex][2], $rowWH[2]);
        }
    }

    public function testGetRowAsAttribute()
    {
        $rows = [
            1 => ['name' => 'Nguyen Van A', 'address' => "Ho Chi Minh city, Vietnam"],
            2 => ['name' => '山田 太郎', 'address' => '東京都、日本'],
        ];
        $attributes = ['name', 'address'];
        foreach ($rows as $rowIndex => $row) {
            $rowWH = $this->arrayWH->getRowByAttributes($rowIndex);
            foreach ($attributes as $attribute) {
                $this->assertEquals($rows[$rowIndex][$attribute], $rowWH[$attribute]);
            }
        }
    }

    public function testAppendRow()
    {
        // Add one row
        $arrayData = $this->arrayWH->getArrayData();
        $newRow = ['New name', 10, 'New address'];
        $rowIndex = $this->arrayWH->appendRow($newRow);
        $newArrayData = $this->arrayWH->getArrayData();
        $countNewArrayData = count($newArrayData);
        $this->assertEquals($countNewArrayData - 1, $rowIndex);
        $this->assertEquals($newRow, $this->arrayWH->getRow($rowIndex));
        $this->assertEquals(count($arrayData) + 1, $countNewArrayData);

        // Add one more row.
        $rowIndex = $this->arrayWH->appendRow($newRow);
        $this->assertEquals($countNewArrayData, $rowIndex);
    }

    public function testSetCell()
    {
        $name = 'New name AAA';
        $age = 200;
        $address = 'New address AAA';
        $this->arrayWH->setCell(1, 0, $name);
        $this->arrayWH->setCell(1, 1, $age);
        $this->arrayWH->setCell(2, 2, $address);
        $arrayData = $this->arrayWH->getArrayData();
        $this->assertEquals($name, $arrayData[1][0]);
        $this->assertEquals($age, $arrayData[1][1]);
        $this->assertEquals($address, $arrayData[2][2]);
    }

    public function testSetCellByAttribute()
    {
        $name = 'New name AAA';
        $age = 200;
        $address = 'New address AAA';
        $this->arrayWH->setCellByAttribute(1, 'name', $name);
        $this->arrayWH->setCellByAttribute(1, 'age', $age);
        $this->arrayWH->setCellByAttribute(2, 'address', $address);
        $arrayData = $this->arrayWH->getArrayData();
        $this->assertEquals($name, $arrayData[1][0]);
        $this->assertEquals($age, $arrayData[1][1]);
        $this->assertEquals($address, $arrayData[2][2]);
    }

    public function testSetRow()
    {
        // Add one row
        $row = ['New name', 10, 'New address'];
        $this->arrayWH->setRow(1, $row);
        $arrayData = $this->arrayWH->getArrayData();
        $this->assertEquals($row, $arrayData[1]);
    }

    public function testSetRowByAttribute()
    {
        // Add one row
        $row = ['name' => 'New name', 'age' => 10, 'address' => 'New address'];
        $this->arrayWH->setRowByAttribute(1, $row);
        $newRow = $this->arrayWH->getRowByAttributes(1);
        $this->assertEquals($row, $newRow);
    }

    public function testAssocToNormalArray()
    {
        $assocRow = ['address' => 'New address Assoc', 'age' => 30, 'name' => 'New name Assoc'];
        $orderRow = $this->arrayWH->assocToNormalArray($assocRow);
        $this->assertEquals(['New name Assoc', 30, 'New address Assoc'], $orderRow);
    }
}