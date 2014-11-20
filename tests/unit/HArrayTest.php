<?php
use Batsg\Util\HArray;

class HArrayTest extends PHPUnit_Framework_TestCase
{
    public function testValueEqual()
    {
        $this->assertTrue(HArray::valueEqual(array(), array()));
        $this->assertFalse(HArray::valueEqual(array(), array(1)));
        $this->assertTrue(HArray::valueEqual(array(1, 'a'), array('a', 1)));
        $this->assertTrue(HArray::valueEqual(array(1, 2), array('2', '1')));
    }
}
?>