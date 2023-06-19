<?php
namespace umbalaconmeogia\phputil\data;

/**
 * This class help dealing with array that has the first row as data header.
 *
 * By using ArrayWithHeadear, we can access to array element data via col name defined in header row.
 *
 * ```
 * @author thanh
 */
class ArrayWithHeader
{
    /**
     * @var array
     */
    private $header;

    /**
     * @var array[]
     */
    private $arrayData;

    /**
     * @var array Mapping between attribute to index on header.
     */
    private $attributeIndexes;

    public function __construct($arrayData)
    {
        $this->arrayData = $arrayData;
        if (count($this->arrayData) == 0) {
            throw new \Exception("Array has no header");
        }
        $this->loadHeader();
    }

    /**
     * Load header row (remember associated key).
     */
    private function loadHeader()
    {
        $this->header = $this->arrayData[0];

        // Set attribute index.
        $this->attributeIndexes = [];
        foreach ($this->header as $index => $attribute) {
            $this->attributeIndexes[$attribute] = $index;
        }
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    public function getAttributeIndexes()
    {
        return $this->attributeIndexes;
    }

    /**
     * @return array[]
     */
    public function getArrayData()
    {
        return $this->arrayData;
    }

    /**
     * Get a row data.
     * @param int $index
     * @param boolean $trim
     * @return array
     */
    public function getRow($index, $trim = TRUE)
    {
        $row = $this->arrayData[$index];
        if ($trim) {
            foreach ($row as $key => $value) {
                $row[$key] = trim($value);
            }
        }
        return $row;
    }

    /**
     * Get specified row as associated array, with keys defied by header
     * @return array
     */
    public function getRowByAttributes($index, $trim = TRUE)
    {
        $row = $this->getRow($index, $trim);
        $rowAsAttributes = []; // Initiate array.
        // Use $header element value as key, to set $rowAsAttributes' value.
        foreach ($this->header as $index => $attribute) {
            if (isset($row[$index])) {
                $rowAsAttributes[$attribute] = $row[$index];
            }
        }
        // Return $rowAsAttributes
        return $rowAsAttributes;
    }

    /**
     * Append a row.
     * @return int The index of added row.
     */
    public function appendRow($rowData = [])
    {
        return array_push($this->arrayData, $rowData) - 1;
    }

    /**
     * @param int $rowIndex
     * @param array $rowValue
     * @param boolean $clearValue
     */
    public function setRow($rowIndex, $rowValue, $clearValue = TRUE)
    {
        if ($clearValue) {
            $this->arrayData[$rowIndex] = [];
        }
        foreach ($rowValue as $cellIndex => $value) {
            $this->setCell($rowIndex, $cellIndex, $value);
            // $this->arrayData[$rowIndex][$cellIndex] = $value;
        }
    }

    /**
     * @param int $rowIndex
     * @param array $rowValue Associated array.
     * @param boolean $clearValue
     */
    public function setRowByAttribute($rowIndex, $rowValue, $clearValue = TRUE)
    {
        if ($clearValue) {
            $this->arrayData[$rowIndex] = [];
        }
        foreach ($rowValue as $attribute => $value) {
            $this->setCellByAttribute($rowIndex, $attribute, $value);
            // $cellIndex = $this->attributeIndexes[$attribute];
            // $this->arrayData[$rowIndex][$cellIndex] = $value;
        }
    }

    /**
     * @param int $rowIndex
     */
    public function setCell($rowIndex, $cellIndex, $value)
    {
        $this->arrayData[$rowIndex][$cellIndex] = $value;
    }

    /**
     * @param int $rowIndex
     * @param string $attribute
     * @param mixed $value
     */
    public function setCellByAttribute($rowIndex, $attribute, $value)
    {
        $this->setCell($rowIndex, $this->attributeIndexes[$attribute], $value);
        // $cellIndex = $this->attributeIndexes[$attribute];
        // $this->arrayData[$rowIndex][$cellIndex] = $value;
    }

    /**
     * Find a row by value of specified (key) column.
     * @param string $attribute
     * @param mixed $value
     * @return int Index of found row. Return NULL if not found.
     */
    public function findRowByAttribute($attribute, $value)
    {
        $result = NULL;
        $cellIndex = $this->attributeIndexes[$attribute];
        foreach ($this->arrayData as $rowIndex => $rowValue) {
            if ($rowValue) {
                if ($rowValue[$cellIndex] == $value) {
                    $result = $rowIndex;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    public function assocToNormalArray($array)
    {
        $result = array_fill(0, count($this->header), '');
        foreach ($array as $attribute => $value) {
            $result[$this->attributeIndexes[$attribute]] = $value;
        }
        return $result;
    }
}