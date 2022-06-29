<?php
use PHPUnit\Framework\TestCase;
use umbalaconmeogia\phputil\UTSample;

final class UTSampleTest extends TestCase
{
    public function testCompare()
    {
        $utSample = new UTSample();
        $this->assertSame(0, $utSample->compare(1, 1));
    }
}