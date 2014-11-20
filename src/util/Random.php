<?php
/**
 * php-batsg-util: PHP utility classes.
 * Copyright (c) <2014> Tran Trung Thanh <umbalaconmeogia@gmail.com>
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
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
 * ``````````````````````````````````````````````````````````````
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
 * ``````````````````````````````````````````````````````````````
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