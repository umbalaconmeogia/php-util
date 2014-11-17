<?php
/**
 * File
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
 * Manipulate file and directory.
 */
class File
{
    /**
     * Get the file extension.
     * TODO: For a file as ".lib", it should return NULL as file extension.
     * @param string $path Path to the file name.
     * @return string File extension (after the last dot .)
     *         or NULL if there is no extension.
     */
    public static function fileExtension($path)
    {
        $pathInfo = pathinfo($path);
        return isset($pathInfo['extension']) ? $pathInfo['extension'] : NULL;
    }

    /**
     * Get the file name (with or without extension).
     * TODO: For a file as ".lib", it should return ".lib" as file name without extension.
     * @param string $path Path to the file name.
     * @return string File name.
     */
    public static function fileName($path, $withExtension = TRUE)
    {
        $pathInfo = pathinfo($path);
        return $withExtension ? $pathInfo['basename'] : $pathInfo['filename'];
    }

   /**
     * Copy a directory recursively.
     * Usage:
     *   File::copy('/path/to/source/dir', '/path/to/dest/parent'); // This will create "dir" in parent.
     *   File::copy('/path/to/source/dir', '/path/to/dest/dir', FALSE); // This will copy sub dir/files from source/dir into dest/dir.
     * @param string $source
     * @param string $dest
     * @param boolean $destIsParentDir
     */
    public static function copy($source, $dest, $destIsParentDir = TRUE) {
        $dir = opendir($source);
        if ($destIsParentDir) {
            $dest = "{$dest}/" . self::fileName($source);
        }
        @mkdir($dest);
        while (FALSE !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $sourcePath = "{$source}/{$file}";
                $destPath = "{$dest}/{$file}";
                if (is_dir($sourcePath)) { // Copy directory.
                    self::copy($sourcePath, $destPath, FALSE);
                } else { // Copy file.
                    copy($sourcePath, $destPath);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Remove directory (recursively).
     * @param string $directory
     * @param boolean $checkDirExistance If TRUE, throw error if $directory does not exist.
     * @throw InvalidArgumentException if $checkDirExistance is TRUE and the directory does not exist.
     */
    public static function rmdir($directory, $checkDirExistance = TRUE)
    {
        if (is_dir($directory)) {
            self::removeDirRecursively($directory);
        } else {
            if ($checkDirExistance) {
                throw new \InvalidArgumentException("Directory $directory does not exist.");
            }
        }
    }

    private static function removeDirRecursively($directory)
    {
        // Remove files and sub-directories.
        foreach (scandir($directory) as $file) {
            if ($file != '.' && $file != '..') {
                $path = "$directory/$file";
                if (is_file($path)) {
                    unlink($path);
                } else {
                    self::removeDirRecursively($path);
                }
            }
        }
        rmdir($directory);
    }
}
?>