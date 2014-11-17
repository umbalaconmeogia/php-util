<?php
/**
 * Random
 *
 * The MIT License (MIT)
 *
 * Copyright (c) <2014> <Tran Trung Thanh <umbalaconmeogia@gmail.com>>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package Batsg\Util
 * @author Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @copyright 2014 Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/umbalaconmeogia/batsg-php-util
 */
namespace Batsg\Util;

/**
 * Functions to generate random string.
 * Usage:
 *   // Quickly generate a random password.
 *   $s = Random::generatePassword();
 *
 *   // Use Random class to generate random string.
 *   $r = new Random();
 *   $s = $r->generateString(); // Default string length.
 *   $s = $r->generateString(4); // Specify string length.
 *
 *   // Specify random character pool by string or array.
 *   $r = new Random('ABC123');
 *   $r = new Random(['A', 'B', 'C', 1, 2, 3]);
 */
class Random
{
    /**
     * Default characters that is used to generate password.
     * @var string
     */
    const DEFAULT_CHARACTER_SET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    /**
     * Default length of generate string.
     * @var int
     */
    const DEFAULT_RANDOM_STRING_LENGTH = 8;

    /**
     * @var string[]
     */
    private $_pool;
    
    /**
     * Generate random password.
     * @param string|array $pool Character set used to generate password.
     */
    public function __construct($pool = self::DEFAULT_CHARACTER_SET)
    {
        if (!is_array($pool)) {
            $pool = str_split($pool);
        }
        $this->_pool = $pool;
    }
    
    /**
     * Generate random string.
     * @param int $length Length of password to be created.
     */
    public function generateString($length = self::DEFAULT_RANDOM_STRING_LENGTH)
    {
        $characterSet = &$this->_pool;
        $string = array();
        for ($i = 0; $i < $length; $i++) {
            $string[] = $characterSet[array_rand($characterSet)];
        }
        return implode($string);
    }
    
    /**
     * Generate random password.
     * @param int $length Length of password to be created.
     * @param string|array $pool Character set used to generate password.
     */
    public static function generatePassword($length = self::DEFAULT_RANDOM_STRING_LENGTH, $pool = self::DEFAULT_CHARACTER_SET)
    {
        $r = new Random($pool);
        return $r->generateString($length);
    }
}
?>