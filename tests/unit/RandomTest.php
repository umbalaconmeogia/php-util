<?php
use Batsg\Util\Random;

class RandomTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        // Default pool.
        $r = new Random();
        $this->assertStringIsInPool($r->generateString(4), Random::DEFAULT_CHARACTER_SET);

        // Constructor accept string pool
        $pool = 'ABC123';
        $r = new Random($pool);
        $this->assertStringIsInPool($r->generateString(4), $pool);

        // Constructor accept array pool
        $pool = ['A', 'B', 'C', '1', '2', '3'];
        $r = new Random($pool);
        $this->assertStringIsInPool($r->generateString(4), $pool);
    }
    
    public function testGenerateString()
    {
        // Default length.
        $r = new Random();
        $s = $r->generateString();
        $this->assertStringIsInPool($s, Random::DEFAULT_CHARACTER_SET);
        $this->assertEquals(Random::DEFAULT_RANDOM_STRING_LENGTH, strlen($s));

        // Specified length.
        $r = new Random();
        $s = $r->generateString(10);
        $this->assertStringIsInPool($s, Random::DEFAULT_CHARACTER_SET);
        $this->assertEquals(10, strlen($s));
    }
    
    public function testGeneratePassword()
    {
        // Default length.
        $s = Random::generatePassword();
        $this->assertStringIsInPool($s, Random::DEFAULT_CHARACTER_SET);
        $this->assertEquals(Random::DEFAULT_RANDOM_STRING_LENGTH, strlen($s));
        
        // Specified length.
        $s = Random::generatePassword(10);
        $this->assertStringIsInPool($s, Random::DEFAULT_CHARACTER_SET);
        $this->assertEquals(10, strlen($s));
    }
    
    /**
     * Check if all characters of $string are in $pool.
     * @param string $string
     * @param string|string[] $pool
     */
    private function assertStringIsInPool($string, $pool)
    {
        $result = TRUE;
        $message = NULL;
        if (!is_array($pool)) {
            $pool = str_split($pool);
        }
        $string = str_split($string);
        foreach ($string as $char) {
            if (!in_array($char, $pool)) {
                $result = FALSE;
                $message = "Character $char is not in pool (" . implode(',', $pool) . ")";
            }
        }
        self::assertThat($result, self::isTrue(), $message);
    }
}
?>