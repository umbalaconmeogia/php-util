<?php
use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\calculation\NumberSystem;

class NumberSystemTest extends TestCase
{
    public function testDigitValue()
    {
        $digitValue = NumberSystem::mapDigitValue('0123456');
        for ($i = '0'; $i <= '6'; $i++) {
            $this->assertEquals((int) $i, $digitValue[$i]);
        }
        $digitValue = NumberSystem::mapDigitValue('0123456789ABCDEF');
        for ($i = '0'; $i <= '9'; $i++) {
            $this->assertEquals((int) $i, $digitValue[$i]);
        }
        $this->assertEquals(10, $digitValue['A']);
        $this->assertEquals(11, $digitValue['B']);
        $this->assertEquals(12, $digitValue['C']);
        $this->assertEquals(13, $digitValue['D']);
        $this->assertEquals(14, $digitValue['E']);
        $this->assertEquals(15, $digitValue['F']);
    }

    public function testConvertBinaryAndDecimal()
    {
        $baseToDec = [
            '0' => 0,
            '1' => 1,
            '10' => 2,
            '11' => 3,
            '100' => 4,
            '101' => 5,
            '110' => 6,
            '111' => 7,
            '1111' => 15,
            '1001001100101100000001011010010' => 1234567890,
        ];
        foreach ($baseToDec as $base => $dec) {
            // echo "$base - $dec\n";
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::BASE_2));
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::DIGIT_BINARY));

            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::BASE_2, $dec));
            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::DIGIT_BINARY, $dec));
        }
    }

    public function testConvertHexAndDecimal()
    {
        $baseToDec = [
            '0' => 0,
            '9' => 9,
            'A' => 10,
            'F' => 15,
            '10' => 16,
            '11' => 17,
            '1F' => 31,
            '20' => 32,
            'FF' => 255,
            '100' => 256,
            '499602D2' => 1234567890,
        ];
        foreach ($baseToDec as $base => $dec) {
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::BASE_16));
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::DIGIT_HEXADECIMAL));

            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::BASE_16, $dec));
            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::DIGIT_HEXADECIMAL, $dec));
        }
    }

    public function testConvertDigitLowerCaseAndDecimal()
    {
        $baseToDec = [
            '0' => 0,
            'a' => 10,
            'f' => 15,
            'g' => 16,
            'h' => 17,
            'z' => 35,
            '10' => 36,
            '1z' => 71,
            '100' => 1296,
        ];
        foreach ($baseToDec as $base => $dec) {
            // echo "$base - $dec\n";
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::DITGIT_LOWER_CASE));

            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::DITGIT_LOWER_CASE, $dec));
        }
    }

    public function testConvertDigitUpperCaseAndDecimal()
    {
        $baseToDec = [
            '0' => 0,
            'A' => 10,
            'F' => 15,
            'G' => 16,
            'H' => 17,
            'Z' => 35,
            '10' => 36,
            '1Z' => 71,
            '100' => 1296,
        ];
        foreach ($baseToDec as $base => $dec) {
            $this->assertEquals($dec, NumberSystem::convertToDecimal($base, NumberSystem::DITGIT_UPPER_CASE));

            $this->assertEquals($base, NumberSystem::convertFromDecimal(NumberSystem::DITGIT_UPPER_CASE, $dec));
        }
    }

    public function testConvertDecimalToDecimal()
    {
        $testValues = [
            12345,
            1234567890,
        ];
        foreach ($testValues as $value) {
            $this->assertEquals($value, NumberSystem::convertToDecimal($value, NumberSystem::BASE_10));
            $this->assertEquals($value, NumberSystem::convertToDecimal($value, NumberSystem::DIGIT_DECIMAL));
            $this->assertEquals($value, NumberSystem::convertFromDecimal(NumberSystem::BASE_10, $value));
            $this->assertEquals($value, NumberSystem::convertFromDecimal(NumberSystem::DIGIT_DECIMAL, $value));
        }
    }

    /**
     * @depends testConvertBinaryAndDecimal
     * @depends testConvertHexAndDecimal
     */
    public function testConvertBinaryAndHex()
    {
        $decValues = [
            0,
            15,
            16,
            31,
            32,
            255,
            256,
            1023,
            1024,
            1234567890,
        ];
        foreach ($decValues as $dec) {
            $binary = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_BINARY, $dec);
            $hex = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_HEXADECIMAL, $dec);
            $this->assertEquals($binary, NumberSystem::convert(NumberSystem::DIGIT_BINARY, $hex, NumberSystem::DIGIT_HEXADECIMAL));
            $this->assertEquals($hex, NumberSystem::convert(NumberSystem::DIGIT_HEXADECIMAL, $binary, NumberSystem::DIGIT_BINARY));
        }
    }

    // public function testSomeValue()
    // {
    //     $fromHex = NumberSystem::convertToDecimal('FF0', NumberSystem::DIGIT_HEXADECIMAL);
    //     echo "fromHex $fromHex\n";
    //     $fromBinary = NumberSystem::convertToDecimal('100110', NumberSystem::DIGIT_BINARY);
    //     echo "fromBinary $fromBinary\n";
    //     $hexValue = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_HEXADECIMAL, 1234567890);
    //     echo "hexValue $hexValue\n";
    //     $binaryValue = NumberSystem::convertFromDecimal(NumberSystem::DIGIT_BINARY, 1234567890);
    //     echo "binaryValue $binaryValue\n";
    //     $digitAndLowerCaseDigits = NumberSystem::convert(NumberSystem::DITGIT_LOWER_CASE, 1234567890, NumberSystem::DIGIT_DECIMAL);
    //     echo "digitAndLowerCaseDigits $digitAndLowerCaseDigits\n";
    //     $hexadecimalValue = NumberSystem::convert(NumberSystem::DIGIT_HEXADECIMAL, 1234567890, NumberSystem::DIGIT_DECIMAL);
    //     echo "hexadecimalValue $hexadecimalValue\n";
    // }

}