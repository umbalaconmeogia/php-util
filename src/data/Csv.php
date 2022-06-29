<?php
namespace umbalaconmeogia\phputil\data;

class Csv
{
    /**
     * Save array to csv file.
     * @param array $array
     * @param string $csvFile
     */
    public static function arrayToCsv($array, $csvFile)
    {
        $handle = fopen($csvFile, 'w');
        foreach ($array as $row)
        {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

    /**
     * Read array from csv file.
     * @param string $csvFile
     * @return array
     */
    public static function csvToArray(string $csvFile): array
    {
        $handle = fopen($csvFile, 'r');

        $result = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            $result[] = $data;
        }

        fclose($handle);
        return $result;
    }
}