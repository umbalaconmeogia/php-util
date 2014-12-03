<?php 
//Define autoloader 
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'umbalaconmeogia\\util\\harray' => '/util/HArray.php',
                'umbalaconmeogia\\util\\hdatetime' => '/util/HDateTime.php',
                'umbalaconmeogia\\util\\random' => '/util/Random.php',
                'umbalaconmeogia\\util\\file\\file' => '/util/file/File.php',
                'umbalaconmeogia\\util\\file\\temporaryfile' => '/util/file/TemporaryFile.php',
                'umbalaconmeogia\\util\\file\\zip' => '/util/file/Zip.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
)
?>