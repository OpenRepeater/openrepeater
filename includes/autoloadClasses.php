<?php
###########################################################################################
# Class Autoloader
###########################################################################################

function __autoload($class_name) {
    $file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/classes/' . $class_name . '.php';

    if (file_exists($file))
        require_once($file);
    else
        die('File Not Found: ' . $file);
}