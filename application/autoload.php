<?php

function __autoload($className){
    /**
     * Library files address
     */
    $fileNameLibrary = FILE_PATH . 'library' . DS . $className . DS . 'index.php';
    /**
     * MVC main files address
     */
    $fileNameMVC = FILE_PATH . 'application' . DS . $className .'.php';
    
    /**
     * At this part we are about to loading library files,
     */
    if(file_exists($fileNameLibrary)){
        require_once $fileNameLibrary;
        return true;
    }
    /**
     * We are going to see whether MVC files are called or not, if they are called then including them,
     */
    
    else if(file_exists($fileNameMVC)){
        require_once $fileNameMVC;
        return true;
    }
    return false;
}