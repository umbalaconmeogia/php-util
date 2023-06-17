<?php
namespace umbalaconmeogia\phputil\calculation;

/**
 * Convert between number systems.
 */
class NumberSystem
{
    const DIGIT_BINARY = '01';
    const DIGIT_OCTAL = '01234567';
    const DIGIT_DECIMAL = '0123456789';
    const DIGIT_HEXADECIMAL = '0123456789ABCDEF';
    const DITGIT_LOWER_CASE = '0123456789abcdefghijklmnopqrstuvwxyz';
    const DITGIT_UPPER_CASE = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    const BASE_2 = 'BASE_2';
    const BASE_8 = 'BASE_8';
    const BASE_10 = 'BASE_10';
    const BASE_16 = 'BASE_16';

    const BASE_DIGIT_MAPPING = [
        self::BASE_2 => self::DIGIT_BINARY,
        self::BASE_8 => self::DIGIT_OCTAL,
        self::BASE_10 => self::DIGIT_DECIMAL,
        self::BASE_16 => self::DIGIT_HEXADECIMAL,
    ];
    /**
     * Convert between number systems.
     *
     * Example usage
     * ```php
     * // Convert a decimal number to digit-and-lower-case number system.
     * $digitAndLowerCaseDigits = NumberSystem::convert(NumberSystem::DITGIT_LOWER_CASE, 1234567890, NumberSystem::DIGIT_DECIMAL);
     * // $digitAndLowerCaseDigits = kf12oi
     *
     * // Convert a decimal number to hexadecimal number.
     * $hexadecimalValue = NumberSystem::convert(NumberSystem::DIGIT_HEXADECIMAL, 1234567890, NumberSystem::DIGIT_DECIMAL);
     * // $hexadecimalValue = 499602D2
     * ```
     *
     * @param string $toDigits
     * @param string $fromValue
     * @param string $fromDigits
     */
    public static function convert($toDigits, $fromValue, $fromDigits = self::DIGIT_DECIMAL)
    {
        $decValue = self::convertToDecimal($fromValue, $fromDigits);
        return self::convertFromDecimal($toDigits, $decValue);
    }

    /**
     * Convert from value expressed by set of digits to decimal.
     *
     * Example usage
     * ```php
     * // Convert from hexadecimal to decimal.
     * $fromHex = NumberSystem::convertToDecimal('FF0', NumberSystem::DIGIT_HEXADECIMAL);
     * // $fromHex = 4080
     *
     * // Convert from binary to decimal.
     * $fromBinary = NumberSystem::convertToDecimal('100110', NumberSystem::DIGIT_BINARY);
     * // $fromBinary = 38
     * ```
     *
     * @param string $fromValue
     * @param string $fromDigits
     * @return int
     */
    public static function convertToDecimal($fromValue, $fromDigits)
    {
        $result = NULL;
        $fromDigits = self::BASE_DIGIT_MAPPING[$fromDigits] ?? $fromDigits;
        if ($fromDigits == self::DIGIT_DECIMAL) {
            $result = $fromValue;
        } else {
            $mapDigitValue = self::mapDigitValue($fromDigits);
            $numberOfDigit = count($mapDigitValue);
            $fromArray = str_split(strrev($fromValue));
            $result = 0;
            $unit = 1;
            foreach ($fromArray as $index => $digit) {
                if ($index > 0) {
                    $unit *= $numberOfDigit;
                }
                $result += $mapDigitValue[$digit] * $unit;
            }
        }
        if (!$result) {
            $result = '0';
        }
        return $result;
    }

    /**
     * Convert from decimal to value expressed by set of digits.
     *
     * Example usage
     * ```php
     * // Convert from decimal to hexadecimal.
     * $hexValue = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_HEXADECIMAL, 1234567890);
     * // $hexValue = 499602D2
     *
     * // Convert from binary to decimal.
     * $binaryValue = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_BINARY, 1234567890);
     * // $binaryValue = 1001001100101100000001011010010
     * ```
     *
     * @param string $toDigits
     * @param string $fromValue
     * @return int
     */
    public static function convertFromDecimal($toDigits, $fromValue)
    {
        $result = NULL;
        $toDigits = self::BASE_DIGIT_MAPPING[$toDigits] ?? $toDigits;
        if ($toDigits == self::DIGIT_DECIMAL) {
            $result = $fromValue;
        } else {
            // echo "FROM* $fromValue\n";
            $mapValueDigit = str_split($toDigits);
            $numberOfDigit = count($mapValueDigit);
            $result = '';
            while ($fromValue > 0) {
                $digit = $mapValueDigit[$fromValue % $numberOfDigit];
                // echo "DIGIT $digit\n";
                $result = "{$digit}{$result}";
                $fromValue = intdiv($fromValue, $numberOfDigit);
                // echo "FROM $fromValue\n";
            }
        }
        if (!$result) {
            $result = 0;
        }
        return $result;
    }

    /**
     * Get value corresponding to each digit in digit string.
     * @return array Mapping between each digit and its value.
     */
    public static function mapDigitValue($digitString)
    {
        $result = [];
        $digitArray = str_split($digitString);
        foreach ($digitArray as $index => $letter) {
            $result[$letter] = $index;
        }
        return $result;
    }
}