<?php
use Batsg\Util\File\TemporaryFile;
use Batsg\Util\File\File;

class TemporaryFileTest extends PHPUnit_Framework_TestCase
{
    
    public function testGenerateFile()
    {
        $prefix = 'tft';
        TemporaryFile::$defaultPrefix = $prefix;
        
        // Generate file in system default dir.
        $file = TemporaryFile::generateFile();
        $this->assertStringStartsWith(sys_get_temp_dir(), $file);
        $this->assertStringStartsWith($prefix, File::fileName($file));
        $this->assertFileExists($file);
        File::delete($file);
        // Generate file in specified dir.
        $file = TemporaryFile::generateFile('.');
        $this->assertStringStartsWith(__DIR__, $file);
        $this->assertFileExists($file);
        File::delete($file);
        // Delete file after generated.
        $file = TemporaryFile::generateFile(NULL, TRUE);
        $this->assertStringStartsWith(sys_get_temp_dir(), $file);
        $this->assertFileNotExists($file);
        // Change file prefix.
        $prefix = 'tf1';
        $file = TemporaryFile::generateFile(NULL, TRUE, $prefix);
        $this->assertStringStartsWith($prefix, File::fileName($file));
        $this->assertFileNotExists($file);
    }
}
?>