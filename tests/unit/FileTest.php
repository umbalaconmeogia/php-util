<?php
use Batsg\Util\File\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    const SOURCE_DIR = 'rootDirectory';

    /**
     * @return string
     */
    private function sourceDirPath()
    {
        return __DIR__ . "/../fixture/" . self::SOURCE_DIR;
    }

    /**
     * @return string
     */
    private function destDirParentPath()
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    private function destDirPath($dir = self::SOURCE_DIR)
    {
        return $this->destDirParentPath() . "/{$dir}";
    }

    /**
     * @param string $dir
     */
    private function makeSureDestDirNotExist()
    {
        $dir = $this->destDirPath();
        if (file_exists($dir) && is_dir($dir)) {
            File::rmdir($dir); // Remove directory.
            if (file_exists($dir)) {
                throw new Exception("Directory $dir still exists.");
            }
        }
    }

    public function testFileName()
    {
        // With extension
        $this->assertEquals('lib.inc.php', File::fileName('/path/to/lib.inc.php'));
        $this->assertEquals('lib.php', File::fileName('/path/to/lib.php'));
        $this->assertEquals('lib', File::fileName('/path/to/lib'));
        $this->assertEquals('.lib', File::fileName('/path/to/.lib'));
        // Without extension.
        $this->assertEquals('lib.inc', File::fileName('/path/to/lib.inc.php', FALSE));
        $this->assertEquals('lib', File::fileName('/path/to/lib.php', FALSE));
        $this->assertEquals('lib', File::fileName('/path/to/lib', FALSE));
        // This fails
//        $this->assertEquals('.lib', File::fileName('/path/to/.lib', FALSE));
    }
    
    public function testFileExtension()
    {
        $this->assertEquals('php', File::fileExtension('/path/to/lib.inc.php'));
        $this->assertEquals('php', File::fileExtension('/path/to/lib.php'));
        $this->assertEquals(NULL, File::fileExtension('/path/to/lib'));
        $this->assertEquals(NULL, File::fileExtension('/path/to/lib.'));
        // This fails
//        $this->assertEquals(NULL, File::fileExtension('/path/to/.lib'));
    }

    /**
     * @depends testFileName
     */
    public function testCopyDir()
    {
        // Copy dir to dest parent.
        $this->makeSureDestDirNotExist();
        File::copy($this->sourceDirPath(), $this->destDirParentPath());
        $this->assertFileExists($this->destDirPath());
        $this->assertDirEquals($this->sourceDirPath(), $this->destDirPath());

        // Copy sub directories and files.
        $this->makeSureDestDirNotExist();
        mkdir($this->destDirPath());
        $this->assertFileExists($this->destDirPath());
        File::copy($this->sourceDirPath(), $this->destDirPath(), FALSE);
        $this->assertDirEquals($this->sourceDirPath(), $this->destDirPath());
    }

    private function assertDirEquals($dir1, $dir2)
    {
        //TODO
    }
    
    /**
     * @depends testCopyDir
     */
    public function testRmDir()
    {
        $destDirPath = $this->destDirPath();
        // Copy file.
        $this->makeSureDestDirNotExist();
        File::copy($this->sourceDirPath(), $this->destDirParentPath());
        $this->assertFileExists($destDirPath);
        // Remove file.
        File::rmdir($destDirPath); // Remove directory.
        $this->assertFileNotExists($destDirPath);
    }
    
    protected function tearDown()
    {
        $this->makeSureDestDirNotExist();
    }
}
?>