<?php
namespace umbalaconmeogia\phputil\number;

class Bitwise
{
    private static $intValueOfBits = [];

    /**
     * Convert a bit string to int value (using bindec()).
     * @param string $bitString
     * @return int
     */
    public static function intValueOfBit($bitString)
    {
        if (!isset(self::$intValueOfBits[$bitString])) {
            self::$intValueOfBits[$bitString] = bindec($bitString);
        }
        return self::$intValueOfBits[$bitString];
    }

    /**
     * Count number of bit 1.
     * @param int $value
     * @return int
     */
    public static function countBit1($value)
    {
        $count = 0;
        while ($value) {
            $count += ($value & 1);
            $value = $value >> 1;
        }
        return $count;
    }

    /**
     * Set bit number <$bitNo> of $originValue to $value
     * @param integer $originValue
     * @param integer $bitNo
     * @param integer $bitValue 0 or 1
     * @return integer The result.
     */
    public static function setBit($originValue, $bitNo, $bitValue)
    {
        $result = 0;
        if ($bitValue) {
            $result = $originValue | (1 << $bitNo);
        } else {
            $result = $originValue & ~(1 << $bitNo);
        }
        return $result;
    }
}