<?php
namespace root\core\LibraryController 
{
    use root\core\interfaces\iLibraryController\iLibraryController;
    
    final class LibraryController implements iLibraryController
    {
        
        public static function call($libraryName, $constructorsArgument = array()) 
        {
            if(!self::libraryExistence($libraryName))
                return 0;

            $classAddress =  self::classAddress($libraryName);
            
            if(self::methodExistence($classAddress, '__construct'))
            {
                $reflectionObject = new \ReflectionClass($classAddress);
                $object = $reflectionObject->newInstanceArgs($constructorsArgument);
            }else
            {
                $object = new $classAddress;
            }
            
            if($object instanceof $classAddress)
                return $object;
            
            return 0;
        }
        
        public static function methodExistence($libraryName, $methodName)
        {
            if(!method_exists($libraryName, $methodName))
                return 0;
            return 1;
        }
        
        public static function libraryExistence($libraryName)
        {
            return class_exists(self::classAddress($libraryName));
        }
        
        public static function classAddress($libraryName)
        {
            return str_replace('*', $libraryName, self::libraryNamespace);
        }
        
        public static function configFileAddress()
        {
            return (FILE_PATH . 'config' . DIRECTORY_SEPARATOR . self::configFileName);
        }
        
        public static function parseConfigFile()
        {
            if(!file_exists(self::configFileAddress()))
            {
                global $registry;
                $registry->error->reportError("(" . self::configFileName . ") file is not found at this direction: (". self::configFileAddress(). ")", __LINE__, __METHOD__, false);
                return 0;
            }
            
            return parse_ini_file(self::configFileAddress(), true);
        }
        
        public static function determineArguments($parsedFileResult)
        {
            $count = 1;
            $finalresultAsArray = array();
            
            foreach($parsedFileResult as $key => $value)
            {
                if($key == ($res = 'Argument:' . $count))
                {
                    foreach($value as $secondKey => $secondValue)
                    {
                        $finalresultAsArray[$secondKey][] = $secondValue;
                    }
                    $count++;
                }
                
            }
            return $finalresultAsArray;
        }
        
        public static function globalizeObject()
        {
            $settings = self::parseConfigFile();
            $argumentsAsArray = array();
            global $registry;
            
            $arguments = self::determineArguments($settings);

            foreach($settings['Properties'] as $properties => $values)
            {
                if(isset($arguments[$properties]))
                {
                    $argumentsAsArray = $arguments[$properties];
                }
                
                $object = self::call($values, $argumentsAsArray);
                
                if(is_a($object, self::classAddress($values)))
                    $registry->{$properties} = $object;
            }
            
            return new LibraryController;
        }
    }
}