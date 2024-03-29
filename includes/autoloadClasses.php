<?php
###########################################################################################
# Class Autoloader
###########################################################################################


function myAutoload($class_name) {
    $file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/classes/' . $class_name . '.php';

    if (file_exists($file))
        require_once($file);
    else
        die('File Not Found: ' . $file);
}

spl_autoload_register('myAutoLoad');