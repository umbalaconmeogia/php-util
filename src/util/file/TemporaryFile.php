<?php
/**
 * Class TemporaryFile
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
namespace Batsg\Util\File;

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