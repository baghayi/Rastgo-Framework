<?php

namespace root\core\interfaces\iLibraryController;

interface iLibraryController 
{
    const libraryNamespace = 'root\library\*\index\*';
    const configFileName   = 'libraryGlobalizing.ini';

    public static function call($libraryName, $constructorsArgument = array());

    public static function libraryExistence($libraryName);

    public static function classAddress($libraryName);

    public static function methodExistence($libraryName, $methodName);
    
    public static function parseConfigFile();
    
    public static function globalizeObject();
    
    public static function configFileAddress();
    
    public static function determineArguments($parsedFileResult);
    
}