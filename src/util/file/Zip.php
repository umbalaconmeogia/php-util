<?php
/**
 * Class Zip
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
 * Manipulate Zip.
 * This requires ZipArchive.
 */
class Zip
{
  /**
   * Add files and sub-directories in a folder to zip file.
   * @param string $folder
   * @param ZipArchive $zipFile
   * @param int $exclusiveLength Number of text to be exclusived from the file path.
   */
  private static function folderToZip($folder, $zipFile, $exclusiveLength) {
    $handle = opendir($folder);
    while ($f = readdir($handle)) {
      if ($f != '.' && $f != '..') { // Ignore . and .. sub directories.
        $filePath = "$folder/$f";
        // Remove prefix from file path before add to zip.
        $localPath = substr($filePath, $exclusiveLength);
        if (is_file($filePath)) { // Add file to zip file.
          $zipFile->addFile($filePath, $localPath);
        } elseif (is_dir($filePath)) { // Add sub-directory to zip file.
          $zipFile->addEmptyDir($localPath); // Add empty sub-directory.
          self::folderToZip($filePath, $zipFile, $exclusiveLength); // Continuing zip the files in the sub-directory.
        }
      }
    }
    closedir($handle);
  }

  /**
   * Zip a folder (including itself).
   * Usage:
   *   <code>HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip');</code>
   *
   * @param string $sourcePath Path of directory to be zip.
   * @param string $outZipPath Path of output zip file.
   */
  public static function zipDir($sourcePath, $outZipPath)
  {
    $pathInfo = pathInfo($sourcePath);
    $parentPath = $pathInfo['dirname'];
    $dirName = $pathInfo['basename'];

    $z = new ZipArchive();
    $z->open($outZipPath, ZIPARCHIVE::CREATE);
    $z->addEmptyDir($dirName);
    self::folderToZip($sourcePath, $z, strlen($sourcePath) - strlen($dirName));
    $z->close();
  }
}
?>