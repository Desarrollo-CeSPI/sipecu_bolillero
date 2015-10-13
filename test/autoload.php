<?php
function __autoload($name) {
    $name_parts = explode("\\",$name);
    array_unshift($name_parts, dirname(__FILE__),'..', 'src');
    $new_name = implode(DIRECTORY_SEPARATOR,$name_parts);
    $file = "$new_name.php";
    if ( file_exists($file)) require $file;
}
