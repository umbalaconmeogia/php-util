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
namespace Batsg\Util\File;

/**
 * Manipulate file and directory.
 */
class File
{
    /**
     * Get the file name (with or without extension).
     * @param string $path Path to the file name.
     * @return string File name.
     */
    public static function getFileName($path, $withExtension = TRUE)
    {
        // pathinfo() cannot get right extension if file start with dot and there is no extension.
        $pathInfo = pathinfo($path);
        $fileName = $pathInfo['basename'];
        $result = $fileName;
        if (!self::fileStartWithDotAndThereIsNoExt($fileName)) { // Not a file start with dot and there is not extension.
            $result = $withExtension ? $pathInfo['basename'] : $pathInfo['filename'];
        }
        return $result;
    }

    /**
     * Get the file extension.
     * @param string $path Path to the file name.
     * @return string File extension (after the last dot .)
     *         or NULL if there is no extension.
     */
    public static function getFileExtension($path)
    {
        // pathinfo() cannot get right extension if file start with dot and there is no extension.
        $pathInfo = pathinfo($path);
        $fileName = $pathInfo['basename'];
        $result = NULL;
        if (!self::fileStartWithDotAndThereIsNoExt($fileName)) { // Not a file start with dot and there is not extension.
            $result = isset($pathInfo['extension']) ? $pathInfo['extension'] : NULL;
        }
        return $result;
    }
    
    /**
     * Get parent directory path of file or directory.
     * @param string $path Path to the file or directory.
     * @param boolean $absolutePath If true, then return the absolute path.
     * @return string Parent directory.
     */
    public static function getFileDir($path, $absolutePath = FALSE)
    {
        $dir = dirname($path);
        if ($absolutePath) {
            $dir = realpath($dir);
        }
        return $dir;
    }

    /**
     * Compare if two files' content are equals.
     * @param string $file1
     * @param string $file2
     * @return boolean TRUE if two files are equal, FALSE otherwise.
     */
    public static function filesEqual($file1, $file2)
    {
      return (filesize($file1) == filesize($file2)) && (md5_file($file1) == md5_file($file2));
    }
    
    /**
     * Compare if directories and files in two directories are the same.
     * @param string $dir1
     * @param string $dir2
     * @return boolean TRUE if two directories are equal, FALSE otherwise.
     */
    public static function dirsEqual($dir1, $dir2)
    {
        $result = TRUE;
        // Compare files.
        $files1 = self::listFileOnly($dir1);
        $files2 = self::listFileOnly($dir2);
        $result = \Batsg\Util\HArray::valueEqual(array_keys($files1), array_keys($files2));
        // Compare files' content
        if ($result) {
            foreach ($files1 as $key => $path1) {
                $path2 = $files2[$key];
                if (!File::filesEqual($path1, $path2)) {
                    $result = FALSE;
                    break;
                }
            }
        }
        // Compare sub directories.
        if ($result) {
            $files1 = self::listDirOnly($dir1);
            $files2 = self::listDirOnly($dir2);
            $result = \Batsg\Util\HArray::valueEqual(array_keys($files1), array_keys($files2));
            if ($result) {
                foreach ($files1 as $key => $path1) {
                    $path2 = $files2[$key];
                    if (!File::dirsEqual($path1, $path2)) {
                        $result = FALSE;
                        break;
                    }
                }
            }
            else {
            }
        }
        return $result;
    }
    
    /**
     * @param string $fileName
     * @return boolean TRUE if file start with dot and there is no extension.
     */
    private static function fileStartWithDotAndThereIsNoExt($fileName)
    {
        $result = preg_match('/^\.[^\.]*$/', $fileName, $matches);
        return $result;
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
            $dest = "{$dest}/" . self::getFileName($source);
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

    /**
     * List files inside specified path (exclude . and ..).
     * @param string $directory
     * @return array of filename => path
     */
    public static function listFileOnly($directory)
    {
        $result = array();
        foreach (scandir($directory) as $file) {
            if ($file != '.' && $file != '..') {
                $path = "$directory/$file";
                if (is_file($path)) {
                    $result[$file] = $path;
                }
            }
        }
        return $result;
    }

    /**
     * List directories inside specified path (exclude . and ..).
     * @param string $directory
     * @return array of dirname => path
     */
    public static function listDirOnly($directory)
    {
        $result = array();
        foreach (scandir($directory) as $file) {
            if ($file != '.' && $file != '..') {
                $path = "$directory/$file";
                if (is_dir($path)) {
                    $result[$file] = $path;
                }
            }
        }
        return $result;
    }
    
    /**
     * Delete a file or directory.
     * @param string $file
     */
    public static function delete($file)
    {
        if (file_exists($file)) {
            if (is_dir($file)) {
                self::rmdir($file);
            } else {
                if (!unlink($file)) {
                    throw new Exception("Error deleting file $file.");
                }
            }
        }
    }
}
?>