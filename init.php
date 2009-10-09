<?php

function _bcform_autoload($class_name) {
    $path = realpath(dirname(__FILE__));
    $pattern = "$path/%s/$class_name.php";
    $dirs = array('libraries', 'helpers');

    foreach ($dirs as $dir) {
        $filename = sprintf($pattern, $dir);
        if (file_exists($filename)) {
            require $filename;
            break;
        }
    }
}
spl_autoload_register('_bcform_autoload');
