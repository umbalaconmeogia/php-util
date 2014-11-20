<?php
use \Batsg\Util\HDateTime;

class HDateTimeTest extends PHPUnit_Framework_TestCase
{
    private $testTimestamp;
    private $testTimestampString;
    private $testYmdHis;
    private $expectedY;
    private $expectedM;
    private $expectedD;
    private $expectedH;
    private $expectedI;
    private $expectedS;
    private $expectedW;
    private $testExpectedArray;
    
    private $testDateTime;
    
    protected function setUp()
    {
        $this->testDateTime = new HDateTime($this->testTimestamp);
    }
    
    public function __construct()
    {
        parent::__construct();
        
        date_timezone_set('Tokyo');
        
        $this->testTimestamp = 1380622272;
        $this->testTimestampString = '1380622272';
        $this->expectedY = 2013;
        $this->expectedM = 10;
        $this->expectedD = 1;
        $this->expectedH = 19;
        $this->expectedI = 11;
        $this->expectedS = 12;
        $this->expectedW = 2;
        $this->testExpectedArray = [
            $this->expectedY,
            $this->expectedM,
            $this->expectedD,
            $this->expectedH,
            $this->expectedI,
            $this->expectedS,
            $this->expectedW,
        ];
        $this->testYmdHis = "{$this->expectedY}/{$this->expectedM}/{$this->expectedD} {$this->expectedH}:{$this->expectedI}:{$this->expectedS}";
    }
    
    public function testConstructor()
    {
        $params = [
            $this->testTimestamp,
            $this->testTimestampString,
            $this->testYmdHis,
        ];
        foreach ($params as $param) {
            $dateTime = new HDateTime($param);
            $this->assertHDateTime($dateTime, $this->testExpectedArray);
        }
    }

    private function assertHDateTime(HDateTime $dateTime, array $expected)
    {
        $dateTimeData = [
            $dateTime->getYear(),
            $dateTime->getMonth(),
            $dateTime->getDay(),
            $dateTime->getHour(),
            $dateTime->getMinute(),
            $dateTime->getSecond(),
            $dateTime->getWDay(),
        ];
        $this->assertEquals($dateTimeData, $expected);
    }

    public function testResetByTimestamp()
    {
        $timestamp = 1416469907;
        $this->testDateTime->resetByTimestamp($timestamp);
        $this->assertEquals($timestamp, $this->testDateTime->getTimestamp());
        $this->assertHDateTime($this->testDateTime, [2014, 11, 20, 16, 51, 47, 4]);
    }

    public function testReset()
    {
        $timestamp = 1416469907;
        $this->testDateTime->reset(2014, 11, 20, 16, 51, 47);
        $this->assertEquals($timestamp, $this->testDateTime->getTimestamp());
    }

    public function testGetYear()
    {
        $this->assertEquals($this->testDateTime->getYear(), $this->expectedY);
    }

    public function testGetMonth()
    {
        $this->assertEquals($this->testDateTime->getMonth(), $this->expectedM);
    }

    public function testGetDay()
    {
        $this->assertEquals($this->testDateTime->getDay(), $this->expectedD);
    }

    public function testGetHour()
    {
        $this->assertEquals($this->testDateTime->getHour(), $this->expectedH);
    }

    public function testGetMinute()
    {
        $this->assertEquals($this->testDateTime->getMinute(), $this->expectedI);
    }

    public function testGetSecond()
    {
        $this->assertEquals($this->testDateTime->getSecond(), $this->expectedS);
    }

    public function testGetWDay()
    {
        $this->assertEquals($this->testDateTime->getWDay(), $this->expectedW);
    }

    public function testToString()
    {
        $this->assertEquals("{$this->expectedY}/{$this->expectedM}", $this->testDateTime->toString('Y/m'));
    }

    public function atestFirstDayOfMonth()
    {
    }

    public function atestLastDayOfMonth()
    {
    }

    public function atestGetDate()
    {
    }

    public function atestAdd()
    {
    }

    public function atestNextNYear()
    {
    }

    public function atestNextNMonth()
    {
    }

    public function atestNextNDay()
    {
    }

    public function atestNextNHour()
    {
    }

    public function atestNextNMinute()
    {
    }

    public function atestNextNSecond()
    {
    }

    public function atestCreateFromString()
    {
        
    }

    public function atestCreateFromYmdHms()
    {
    }

    public function atestCreateFromTimestamp()
    {
    }

    public function atestNow()
    {
    }
}
?>