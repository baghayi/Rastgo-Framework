<?php
namespace root\core\Loader;

final class Loader
{
    private static $registry = NULL;

    public function loadModel($ModleName)
    {
        $ModelCompleteName = $ModleName . 'Model';
        $ModelPath = FILE_PATH . 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $ModelCompleteName . '.php';

        if (file_exists($ModelPath))
        {
            require_once $ModelPath;


            if (class_exists($ModelCompleteName))
            {
                self::$registry->model = new $ModelCompleteName();

                return;
            }
            else{
                self::$registry->error->reportError ('Model Class Does Not Exists!', __LINE__, __METHOD__, true);
                return;
            }
        }else {
            self::$registry->error->reportError('Model File Could Not Be Found!', __LINE__, __METHOD__, true);
            return;
        }
    }

    public static function setAutoLoader(\root\core\Registry\Registry $registry)
    {
        self::$registry = $registry;
        spl_autoload_register(array(__class__, 'autoLoader'));
        return;
    }

    private static function autoLoader($namespace)
    {

        /**
         * First, We are Converting the name space to an array.
         */
        $filePath = explode('\\', $namespace);

        /**
         * To remove the Class name (Not class file name) from the end of the namespace.
         */
        array_pop($filePath);
        /**
         * to remove the root key from the beginning the namespace.
         */
        array_shift($filePath);
        
        /**
         * After taking and removing those stuffs, then we are converting namespace from array to an string .
         * using the directory separator that it can work perfectly in every OS (like: windows, linux, ...) .
         */
        $filePath = implode(DIRECTORY_SEPARATOR, $filePath);

        /**
         * Now we have just removed that root key from the beginning of the namespace that was extra and also
         * took the Class name (not its file name) from the namespace.
         * And now we have got the address to the class file from the root of the Framework.
         * 
         * It means that with combining the (FILE_PATH) constant that gives us the absolute path to the Framework's root and having the address of the class name in the
         * Framework (that we it is) and adding the file extension to these things we can have the full path to that class that was called!
         */
        $filePath = FILE_PATH . $filePath . '.php';
        
        /*
         * We are checking to see whether that class exists or not,
         * If it exists then we are calling that class with using the (require_once) function.
         */
        if (is_readable($filePath))
        {
            require_once $filePath;
            self::registryInjection($namespace);

            return;
        }else
        {
            self::$registry->error->reportError("The Called Class File Does Not Exists! ({$filePath})", __LINE__, __METHOD__, false);
            return;
        }
        return;
    }

    private static function registryInjection($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if( $reflectionClass->isInterface() === FALSE &&
            $reflectionClass->hasProperty('registry') &&
            $reflectionClass->getProperty('registry')->isPublic())
        {
            $reflectionClass->setStaticPropertyValue('registry', self::$registry);
            return;
        }

        return;
    }
}
