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
     * Modeles file
     */
    $fileModels = FILE_PATH . 'application' . DS . 'models' . DS . $className .'.php';
    
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
    
    /**
     * We are checking whether there is any file in model directory or not,
     * If there is then including it,
     */
    else if(file_exists($fileModels)){
        require_once $fileModels;
        return true;
    }
    return false;
}