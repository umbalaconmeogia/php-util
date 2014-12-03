<?php 
//Define autoloader 
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'umbalaconmeogia\\util\\harray' => '/HArray.php',
                'umbalaconmeogia\\util\\hdatetime' => '/HDateTime.php',
                'umbalaconmeogia\\util\\random' => '/Random.php',
                'umbalaconmeogia\\util\\file\\file' => '/file/File.php',
                'umbalaconmeogia\\util\\file\\temporaryfile' => '/file/TemporaryFile.php',
                'umbalaconmeogia\\util\\file\\zip' => '/file/Zip.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
)
?>