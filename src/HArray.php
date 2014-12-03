<?php
/**
 * php-batsg-util: PHP utility classes.
 * Copyright (c) <2014> Tran Trung Thanh <umbalaconmeogia@gmail.com>
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package Umbalaconmeogia\Util
 * @author Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @copyright 2014 Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/umbalaconmeogia/php-util
 */
namespace Umbalaconmeogia\Util;

/**
 * Manipulate array functions.
 */
class HArray
{
    /**
     * Check if values of two arrays are equal, ignoring the array indexes.
     * Number and number string are equal.
     * @param array $a1
     * @param array $a2
     * @return boolean TRUE if two array equal, FALSE otherwise.
     */
    public static function valueEqual(array $a1, array $a2)
    {
        return !array_diff($a1, $a2) && !array_diff($a2, $a1);
    }
}
?>