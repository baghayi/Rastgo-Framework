<?php

function __autoload($className){
    /**
     * At this part we are about to loading library files,
     */
    $fileName = FILE_PATH . 'library' . DS . $className . DS . 'index.php';
    if(file_exists($fileName)){
        require_once $fileName;
        return true;
    }
    return false;
}