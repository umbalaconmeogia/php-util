<?php 
//Define autoloader 
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'batsg\\util\\harray' => '/util/HArray.php',
                'batsg\\util\\random' => '/util/Random.php',
                'batsg\\util\\file\\file' => '/util/file/File.php',
                'batsg\\util\\file\\temporaryfile' => '/util/file/TemporaryFile.php',
                'batsg\\util\\file\\zip' => '/util/file/Zip.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
)
?>