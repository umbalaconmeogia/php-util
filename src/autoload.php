<?php 
//Define autoloader 
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'batsg\\util\\random' => '/util/Random.php',
                'batsg\\util\\file\\zip' => '/util/file/Zip.php',
                'batsg\\util\\file\\file' => '/util/file/File.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
)
?>