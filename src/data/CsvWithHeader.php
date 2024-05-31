<?php
namespace umbalaconmeogia\phputil\data;

/**
 * This class help dealing with CSV file that has the first row as data header.
 *
 * By using CsvWithHeader, we can access to CSV element data via col name defined in header row.
 *
 * Example of usage:
 * ```php
 *   CsvWithHeader::read('employee.csv', function($csv) {
 *       while ($csv->loadRow() !== FALSE) {
 *           // Get attributes as an array.
 *           $attr = $csv->getRowAsAttributes();
 *           // Display "name" attribute of data row.
 *           echo 'Employee name: ' . $attr['name'] . "\n";
 *       }
 *   });
 * ```
 *
 * If you have CSV file content in memory (for example, file content is post from form), use CsvWithHeader as below.
 * ```php
 *   $stream = fopen('php://memory', 'r+');
 *   fwrite($stream, $csvText);
 *   rewind($stream);
 *   CsvWithHeader::read($stream, function($csv) {
 *       while ($csv->loadRow() !== FALSE) {
 *           // Get attributes as an array.
 *           $attr = $csv->getRowAsAttributes();
 *           // Display "name" attribute of data row.
 *           echo 'Employee name: ' . $attr['name'] . "\n";
 *       }
 *   });
 * ```
 * @author thanh
 */
class CsvWithHeader
{
    public $csvEscape = '\\';

    public $csvDelimiter = ',';

    public $csvEnclosure = '"';

    private $csvFile;

    private $handle;

    private $header;

    private $row;

    /**
     * @var array Mapping between attribute to value.
     */
    private $rowAsAttributes;

    /**
     * @var array Mapping between attribute to index on header.
     */
    private $attributeIndexes;

    /**
     * Read a CSV file with header.
     * <p />
     * This will open the CSV file, and load header from the first row.
     * <p />
     * Example of usage:
     * ```php
     *   CsvWithHeader::read('employee.csv', function($csv) {
     *       while ($csv->loadRow() !== FALSE) {
     *           // Get attributes as an array.
     *           $attr = $csv->getRowAsAttributes();
     *           // Display "name" attribute of data row.
     *           echo 'Employee name: ' . $attr['name'] . "\n";
     *       }
     *   });
     * ```
     * @param string $csvFile
     * @param Closure $callback Call back function that receives CsvWithHeader as parameter.
     */
    public static function read($csvFile, $callback)
    {
        $csvWithHeader = new CsvWithHeader();
        $csvWithHeader->fopen($csvFile);
        $csvWithHeader->ignoreBomCharacters();
        $csvWithHeader->loadHeader();
        call_user_func($callback, $csvWithHeader);
        $csvWithHeader->fclose();
    }

    /**
     * Write a CSV file with header.
     * <p />
     * This will open the CSV file, and write header to the first row.
     * <p />
     * Example of usage:
     * ```php
     *   $headers = ['id', 'name', 'bod'];
     *   CsvWithHeader::write('employee.csv', function($csv) {
     *           // Write data to CSV.
     *   }, $headers);
     * ```
     * @param string $csvFile
     * @param Closure $callback Call back function that receives CsvWithHeader as parameter.
     * @param string[] $header Header to write to file.
     */
    public static function write($csvFile, $callback, $header = NULL)
    {
        $csvWithHeader = self::openWrite($csvFile, $header);
        call_user_func($callback, $csvWithHeader);
        $csvWithHeader->fclose();
    }

    /**
     * Open a file for write.
     * <p />
     * This will open the CSV file, and write header to the first row.
     * <p />
     * Example of usage:
     * ```php
     *   $headers = ['id', 'name', 'bod'];
     *   $csvWithHeader = CsvWithHeader::openWrite('employee.csv', $headers);
     *   // Write data to CSV using following method calls
     *   // $csvWithHeader->writeRow()
     *   // $csvWithHeader->setRow()->writeRow()
     *   // $csvWithHeader->setRowAsAttributes()->writeRow()
     *   $csvWithHeader->fclose();
     * ```
     * @param string[] $header Header to write to file.
     */
    public static function openWrite($csvFile, $header = NULL)
    {
        $csvWithHeader = new CsvWithHeader();
        $csvWithHeader->fopen($csvFile, 'w');
        if ($header) {
            $csvWithHeader->setHeader($header)->writeRow();
        }
        return $csvWithHeader;
    }

    /**
     * Open an CSV file.
     * @param string|resource $csvFile A CSV file name, or handle of opened file.
     * @param string $mode See fopen()
     */
    public function fopen($csvFile, $mode = 'r')
    {
        if (is_string($csvFile)) {
            $this->csvFile = $csvFile;
            $this->handle = fopen($csvFile, $mode);
        } else {
            $this->handle = $csvFile;
        }
    }

    /**
     * Read over BOM characters at header of file if exists.
     */
    public function ignoreBomCharacters()
    {
        // BOM as a string for comparison.
        $bom = "\xef\xbb\xbf";
        // Progress file pointer and get first 3 characters to compare to the BOM string.
        if (fgets($this->handle, 4) !== $bom) {
            // BOM not found - rewind pointer to start of file.
            rewind($this->handle);
        }
    }

    /**
     * Load and ignore several rows.
     * @param integer $rowNum
     */
    public function skipRow($rowNum = 1)
    {
        for ($i = 0; $i < $rowNum; $i++) {
            $this->loadRow();
        }
    }

    /**
     * Load a CSV row.
     * <p />
     * The loaded data can be accessed via getRow() or getRowAsAttributes().
     *
     * @param boolean $trim Trim value or not.
     * @return array
     */
    public function loadRow($trim = TRUE)
    {
        $this->row = fgetcsv($this->handle, 0, $this->csvDelimiter, $this->csvEnclosure, $this->csvEscape);
        if ($trim && $this->row) {
            foreach ($this->row as $key => $value) {
                $this->row[$key] = $value ? trim($value) : $value;
            }
        }
        $this->rowAsAttributes = NULL;
        return $this->row;
    }

    /**
     * Get the loaded row.
     * @return array
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set row data.
     * @param array $row
     * @return CsvWithHeader This object.
     */
    public function setRow($row)
    {
        $this->row = $row;
        $this->rowAsAttributes = NULL;
        return $this;
    }

    /**
     * Set row data by an associate array.
     * @param array $assocRow
     * @return CsvWithHeader This object.
     */
    public function setRowAsAttributes($assocRow)
    {
        $row = [];
        foreach ($this->header as $index => $attribute) {
            $row[$index] = $assocRow[$attribute] ?? NULL;
        }
        $this->setRow($row);
        return $this;
    }

    /**
     * Set row data and write to file.
     * @param array $row If not specified, then $this->row is written.
     * @return CsvWithHeader This object.
     */
    public function writeRow($row = NULL)
    {
        if ($row === NULL) {
            $row = $this->row;
        }
        fputcsv($this->handle, $row);
        return $this;
    }

    /**
     * Get the loaded row as associated array, with keys defied by header
     * @return array
     */
    public function getRowAsAttributes()
    {
        // Parse $this->row to $this->rowAsAttributes if it is not parsed.
        if (!$this->rowAsAttributes) {
            $this->rowAsAttributes = []; // Initiate array.
            // Use $header element value as key, to set $rowAsAttributes' value.
            foreach ($this->header as $index => $attribute) {
                if (isset($this->row[$index])) {
                    $this->rowAsAttributes[$attribute] = $this->row[$index];
                }
            }
        }
        // Return $rowAsAttributes
        return $this->rowAsAttributes;
    }

    /**
     * Load header row (remember associated key).
     * @param boolean $trim Trim value or not.
     */
    public function loadHeader($trim = TRUE)
    {
        $this->setHeader($this->loadRow($trim));
    }

    /**
     * Add new attribute into header.
     * @param string|string[] $attributes
     */
    public function addHeader($attributes)
    {
        // Assure $attributes is an array.
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }
        foreach ($attributes as $attribute) {
            if (!in_array($attribute, $this->header)) {
                $this->header[] = $attribute;
            }
        }
        $this->setHeader($this->header);
    }

    public function fclose()
    {
        fclose($this->handle);
    }

    /**
     * Set header by specified array. Also reset attribute indexes.
     * @param string[] $header
     * @return CsvWithHeader This object.
     */
    public function setHeader($header)
    {
        $this->row = $this->header = $header;
        $this->resetAttributeIndexes();
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    private function resetAttributeIndexes()
    {
        $this->attributeIndexes = [];
        foreach ($this->header as $index => $attribute) {
            $this->attributeIndexes[$attribute] = $index;
        }
    }

    /**
     * @param string $attribute
     * @param mixed $value
     */
    public function setRowAttribute($attribute, $value)
    {
        $this->getRowAsAttributes();
        $this->rowAsAttributes[$attribute] = $value;
        $this->row[$this->attributeIndexes[$attribute]] = $value;
    }
}