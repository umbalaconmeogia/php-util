<?php
use \Batsg\Util\DateTime;

class DateTimeTest extends PHPUnit_Framework_TestCase
{
    private $testTimeStamp;
    private $testTimeStampString;
    private $testYmdHis;
    private $testExpectedArray;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->testTimeStamp = 1416467454;
        $this->testTimeStampString = '1416467454';
        list($y, $m, $d, $h, $i, $s) = [
            date('Y', $this->testTimeStamp),
            date('m', $this->testTimeStamp),
            date('d', $this->testTimeStamp),
            date('H', $this->testTimeStamp),
            date('i', $this->testTimeStamp),
            date('s', $this->testTimeStamp),
        ];
        $this->testExpectedArray = [$y, $m, $d, $h, $i, $s];
        $this->testYmdHis = "$y/$m/$d $h:$i:$s";
    }
    
    public function testConstructor()
    {
        $params = [
            $this->testTimeStamp,
            $this->testTimeStampString,
            $this->testYmdHis,
        ];
        foreach ($params as $param) {
            $dateTime = new DateTime($param);
            $this->assertDateTime($dateTime, $this->testExpectedArray);
        }
    }

    private function assertDateTime(DateTime $dateTime, array $expected)
    {
        $dateTimeData = [
            $dateTime->getYear(),
            $dateTime->getMonth(),
            $dateTime->getDay(),
            $dateTime->getHour(),
            $dateTime->getMinute(),
            $dateTime->getSecond(),
        ];
        while (count($expected) < count($dateTimeData)) {
            $expected[] = 0;
        }
        $this->assertEquals($dateTimeData, $expected);
    }
    
    public function testResetByTimestamp()
    {
    }

    public function testReset()
    {
    }

    public function testGetYear()
    {
    }

    public function testGetMonth()
    {
    }

    public function testGetDay()
    {
    }

    public function testGetHour()
    {
    }

    public function testGetMinute()
    {
    }

    public function testGetSecond()
    {
    }

    public function testGetWDay()
    {
    }

    public function testGetTimestamp()
    {
    }

    public function testToString()
    {
    }

    public function testFirstDayOfMonth()
    {
    }

    public function testLastDayOfMonth()
    {
    }

    public function testGetDate()
    {
    }

    public function testAdd()
    {
    }

    public function testNextNYear()
    {
    }

    public function testNextNMonth()
    {
    }

    public function testNextNDay()
    {
    }

    public function testNextNHour()
    {
    }

    public function testNextNMinute()
    {
    }

    public function testNextNSecond()
    {
    }

    public function testCreateFromString()
    {
        
    }

    public function testCreateFromYmdHms()
    {
    }

    public function testCreateFromTimestamp()
    {
    }

    public function testNow()
    {
    }
}
?>