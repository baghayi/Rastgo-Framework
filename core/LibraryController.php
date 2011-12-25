<?php
namespace root\core\LibraryController 
{
    use root\core\interfaces\iLibraryController\iLibraryController;
    
    final class LibraryController implements iLibraryController
    {
        
        public function call($libraryName, $constructorsArgument = array()) 
        {
            if(!$this->libraryExistence($libraryName))
                return 0;

            $classAddress =  $this->classAddress($libraryName);
            
            if($this->methodExistence($classAddress, '__construct'))
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
        
        public function methodExistence($libraryName, $methodName)
        {
            if(!method_exists($libraryName, $methodName))
                return 0;
            return 1;
        }
        
        public function libraryExistence($libraryName)
        {
            return class_exists($this->classAddress($libraryName));
        }
        
        public function classAddress($libraryName)
        {
            return str_replace('*', $libraryName, LibraryController::libraryNamespace);
        }
        
    }
}