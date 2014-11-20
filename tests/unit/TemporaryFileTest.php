<?php
use Batsg\Util\File\TemporaryFile;
use Batsg\Util\File\File;

class TemporaryFileTest extends PHPUnit_Framework_TestCase
{
    
    public function testGenerateFile()
    {
        $prefix = 'tft';
        TemporaryFile::$defaultPrefix = $prefix;
        
        $systemTempDirPath = realpath(sys_get_temp_dir());
        
        // Generate file in system default dir.
        $file = TemporaryFile::generateFile();
        $this->assertStringStartsWith($systemTempDirPath, $file);
        $this->assertStringStartsWith($prefix, File::getFileName($file));
        $this->assertFileExists($file);
        File::delete($file);
        // Generate file in specified dir.
        $file = TemporaryFile::generateFile(__DIR__);
        $this->assertStringStartsWith(__DIR__, $file);
        $this->assertFileExists($file);
        File::delete($file);
        // Delete file after generated.
        $file = TemporaryFile::generateFile(NULL, TRUE);
        $this->assertStringStartsWith($systemTempDirPath, $file);
        $this->assertFileNotExists($file);
        // Change file prefix.
        $prefix = 'tf1';
        $file = TemporaryFile::generateFile(NULL, TRUE, $prefix);
        $this->assertStringStartsWith($prefix, File::getFileName($file));
        $this->assertFileNotExists($file);
    }
    
    public function testWriteContentToFile()
    {
        $content = 'abc';
        
        $file = TemporaryFile::writeContentToFile($content);
        $this->assertFileExists($file);
        $this->assertEquals($content, file_get_contents($file));
        File::delete($file);
    }
}
?>