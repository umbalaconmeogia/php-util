<?php
use Batsg\Util\File\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    const SOURCE_DIR = 'rootDirectory';

    /**
     * Get the path of source directory.
     * @param string $subDir If set, then return path to this subdirectory. If not set, then return the path to root directory.
     * @return string
     */
    private function sourceDirPath($subDir = NULL)
    {
        $result = __DIR__ . "/../fixture/" . self::SOURCE_DIR;
        if ($subDir) {
            $result .= "/$subDir";
        }
        return $result;
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
    
    protected function tearDown()
    {
        $this->assureDestDirNotExist();
    }

    /**
     * @param string $dir
     */
    private function assureDestDirNotExist()
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
        // File not start with dot, get extension.
        $this->assertEquals('lib.inc.php', File::getFileName('/path/to/lib.inc.php'));
        $this->assertEquals('lib.php', File::getFileName('/path/to/lib.php'));
        $this->assertEquals('lib', File::getFileName('/path/to/lib'));
        // File start with dot, get extension.
        $this->assertEquals('.lib.inc.php', File::getFileName('/path/to/.lib.inc.php'));
        $this->assertEquals('.lib.php', File::getFileName('/path/to/.lib.php'));
        $this->assertEquals('.lib', File::getFileName('/path/to/.lib'));
        // File not start with dot, don't get extension.
        $this->assertEquals('lib.inc', File::getFileName('/path/to/lib.inc.php', FALSE));
        $this->assertEquals('lib', File::getFileName('/path/to/lib.php', FALSE));
        $this->assertEquals('lib', File::getFileName('/path/to/lib', FALSE));
        // File start with dot, don't get extension.
        $this->assertEquals('.lib.inc', File::getFileName('/path/to/.lib.inc.php', FALSE));
        $this->assertEquals('.lib', File::getFileName('/path/to/.lib.php', FALSE));
        $this->assertEquals('.lib', File::getFileName('/path/to/.lib', FALSE));
    }
    
    public function testFileExtension()
    {
        // File not start with dot.
        $this->assertEquals('php', File::getFileExtension('/path/to/lib.inc.php'));
        $this->assertEquals('php', File::getFileExtension('/path/to/lib.php'));
        $this->assertEquals(NULL, File::getFileExtension('/path/to/lib'));
        $this->assertEquals(NULL, File::getFileExtension('/path/to/lib.'));
        // File start with dot
        $this->assertEquals('php', File::getFileExtension('/path/to/.lib.inc.php'));
        $this->assertEquals('php', File::getFileExtension('/path/to/.lib.php'));
        $this->assertEquals(NULL, File::getFileExtension('/path/to/.lib'));
        $this->assertEquals(NULL, File::getFileExtension('/path/to/.lib.'));
    }

    public function testFileDir()
    {
        $this->assertEquals(__DIR__, File::getFileDir(__FILE__));
        $this->assertEquals(realpath(__DIR__), File::getFileDir(__FILE__, TRUE));
    }
    
    /**
     * @depends testFileName
     */
    public function testCopyDir()
    {
        // Copy dir to dest parent.
        $this->assureDestDirNotExist();
        File::copy($this->sourceDirPath(), $this->destDirParentPath());
        $this->assertFileExists($this->destDirPath());
        $this->assertDirsEqual($this->sourceDirPath(), $this->destDirPath());

        // Copy sub directories and files.
        $this->assureDestDirNotExist();
        mkdir($this->destDirPath());
        $this->assertFileExists($this->destDirPath());
        File::copy($this->sourceDirPath(), $this->destDirPath(), FALSE);
        $this->assertDirsEqual($this->sourceDirPath(), $this->destDirPath());
    }

    /**
     * @depend testFilesEqual
     */
    public function testDirsEqual()
    {
        $testCases = array(
            'equal2' => TRUE,
            'diffDirName' => FALSE,
            'diffFileName' => FALSE,
            'diffFileContent' => FALSE,
        );
        foreach ($testCases as $dir => $result) {
            $this->assertEquals($result, File::dirsEqual($this->sourceDirPath('testEqual/equal1'), $this->sourceDirPath("testEqual/$dir")));
        }
    }
    
    private function assertDirsEqual($dir1, $dir2)
    {
        $this->assertTrue(File::dirsEqual($dir1, $dir2));
    }
    
    public function testFilesEqual()
    {
        $this->assertTrue(File::filesEqual(
            $this->sourceDirPath('testEqual/equalFile1'), $this->sourceDirPath('testEqual/equalFile2')));
    }
    
    /**
     * @depends testCopyDir
     */
    public function testRmDir()
    {
        $destDirPath = $this->destDirPath();
        // Copy file.
        $this->assureDestDirNotExist();
        File::copy($this->sourceDirPath(), $this->destDirParentPath());
        $this->assertFileExists($destDirPath);
        // Remove file.
        File::rmdir($destDirPath); // Remove directory.
        $this->assertFileNotExists($destDirPath);
    }
    
    /**
     * @depends testFileName
     */
    public function testListFileOnly()
    {
        $files = File::listFileOnly($this->sourceDirPath('testList'));
        $this->assertCount(3, $files);
        $keys = array('.dot.txt', 'file1', 'file2');
        foreach ($keys as $key) {
            $filePath = $files[$key];
            $this->assertArrayHasKey($key, $files);
            $this->assertEquals($key, File::getFileName($filePath));
            $this->assertFileExists($filePath);
            $this->assertTrue(is_file($filePath));
        }
    }
    
    /**
     * @depends testFileName
     */
    public function testListDirOnly()
    {
        $files = File::listDirOnly($this->sourceDirPath('testList'));
        $this->assertCount(2, $files);
        $keys = array('dir1', 'dir2');
        foreach ($keys as $key) {
            $filePath = $files[$key];
            $this->assertArrayHasKey($key, $files);
            $this->assertEquals($key, File::getFileName($filePath));
            $this->assertFileExists($filePath);
            $this->assertTrue(is_dir($filePath));
        }
    }
    
    public function testDelete()
    {
        // Copy dir to dest parent.
        $this->assureDestDirNotExist();
        File::copy($this->sourceDirPath(), $this->destDirParentPath());
        
        $file = $this->destDirPath() . '/file1.txt';
        $this->assertFileExists($file);
        File::delete($file);
        $this->assertFileNotExists($file);
        
        $file = $this->destDirPath();
        File::delete($file);
        $this->assertFileNotExists($file);        
    }
}
?>