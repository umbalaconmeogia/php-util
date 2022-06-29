<?php
use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\Csv;

final class CsvTest extends TestCase
{
    /**
     * Data to write to CSV file.
     */
    private $dataArray = [
        [1, 2, 3],
        [2, 3, 4],
        [3, 4, 5],
    ];

    private function writeCsvFile()
    {
        $csvFile = tempnam("./", "tmp");

        Csv::arrayToCsv($this->dataArray, $csvFile);

        return $csvFile;
    }

    private function readCsvFile(string $csvFile): array
    {
        $handle = fopen($csvFile, 'r');

        $result = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            $result[] = $data;
        }

        fclose($handle);
        return $result;
    }

    public function testArrayToCsv(): void
    {
        $csvFile = $this->writeCsvFile();
        $loadCsvData = $this->readCsvFile($csvFile);

        $this->assertFileExists($csvFile);
        $this->assertEquals(count($this->dataArray), count($loadCsvData));
        for ($row = 0; $row < count($this->dataArray); $row++) {
            $nCol = count($this->dataArray[$row]);
            for ($col = 0; $col < $nCol; $col++) {
                $this->assertEquals($this->dataArray[$row][$col], $loadCsvData[$row][$col]);
            }
        }

        unlink($csvFile);
    }
}