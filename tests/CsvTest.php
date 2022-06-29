<?php
use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\data\Csv;

final class CsvTest extends TestCase
{
    private static function dataArray(): array
    {
        return CsvData::$dataArray;
    }

    /**
     * @return string Created csv file name.
     */
    public static function writeCsvFile($csvData): string
    {
        $csvFile = tempnam("./", "tmp");

        Csv::arrayToCsv($csvData, $csvFile);

        return $csvFile;
    }

    public function testCsvToArray(): array
    {
        $csvFile = __DIR__ . "/fixtures/people.csv";
        $csvData = Csv::csvToArray($csvFile);

        $numberOfRows = count($csvData);
        $header = $csvData[0];
        $this->assertEquals(3, $numberOfRows);
        $this->assertEquals('name', $header[0]);
        $this->assertEquals('age', $header[1]);
        $this->assertEquals('address', $header[2]);

        return $csvData;
    }

    /**
     * @depends testCsvToArray
     */
    public function testArrayToCsv(array $fixtureCsvData): void
    {
        $csvFile = $this->writeCsvFile($fixtureCsvData);
        $tempCsvData = Csv::csvToArray($csvFile);

        $this->assertFileExists($csvFile);
        $this->assertEquals(count($tempCsvData), count($fixtureCsvData));
        for ($row = 0; $row < count($tempCsvData); $row++) {
            $nCol = count($tempCsvData[$row]);
            for ($col = 0; $col < $nCol; $col++) {
                $this->assertEquals($tempCsvData[$row][$col], $fixtureCsvData[$row][$col]);
            }
        }

        unlink($csvFile);
    }
}