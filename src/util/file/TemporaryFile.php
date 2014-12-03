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
namespace Umbalaconmeogia\Util\File;

/**
 * Manipulate php temporary file.
 */
class TemporaryFile
{
    /**
     * NOTE: Windows uses only the first three characters of prefix.
     * @var string
     */
    public static $defaultPrefix = 'batsg';

    /**
     * Generate a path to temporary file.
     *
     * @param string $prefix
     * @return string Return the file path.
     */
    public static function generateFile($tempDir = NULL, $deleteFile = FALSE, $prefix = NULL)
    {
        if ($tempDir == NULL) {
            $tempDir = sys_get_temp_dir();
        }
        if ($prefix == NULL) {
            $prefix = self::$defaultPrefix;
        }
        $filePath = tempnam($tempDir, $prefix);
        if ($deleteFile) {
            File::delete($filePath);
        }
        return $filePath;
    }

    /**
     * Write a content to a temporary file.
     * @param string $content
     * @param string $filePath If not specified, then a new file is created.
     * @return string Return the file path.
     */
    public static function writeContentToFile($content, $filePath = NULL)
    {
        if (!$filePath) {
            $filePath = self::generateFile();
        }
        if (file_put_contents($filePath, $content) === FALSE) {
            throw new Exception("Error while write content to file $filePath");
        }
        return $filePath;
    }
}
?>