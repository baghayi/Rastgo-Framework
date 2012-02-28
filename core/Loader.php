<?php
namespace root\core\Loader;
final class Loader {

    public function loadModel($ModleName) {
        global $registry;

        $ModelCompleteName = $ModleName . 'Model';
        $ModelPath = FILE_PATH . 'application' . DS . 'models' . DS . $ModelCompleteName . '.php';

        if (file_exists($ModelPath)) {
            require_once $ModelPath;

            if (class_exists($ModelCompleteName)){
                $registry->model = new $ModelCompleteName;
                return 1;
            }
            else{
                $registry->error->reportError ('Model Class Does Not Exists!', __LINE__, __METHOD__, true);
                return;
            }
        }else {
            $registry->error->reportError('Model File Could Not Be Found!', __LINE__, __METHOD__, true);
            return;
        }
    }

    public static function setAutoLoader() {
        spl_autoload_register(array(__class__, 'autoLoader'));
        return;
    }

    private static function autoLoader($namespace) {
        /**
         * First, We are Converting the name space to an array.
         */
        $filePath = explode('\\', $namespace);
        /**
         * To remove the Class name (Not class file name) from the end of the namespace.
         */
        array_pop($filePath);
        /**
         * to remove the root key from the beggining of the namespace.
         */
        array_shift($filePath);
        
        /**
         * After taking and removing those stuffs, then we are converting namespace from array to an string .
         * using the directory seperator that it can work perfectly in every OS (like: windows, linux, ...) .
         */
        $filePath = implode(DS, $filePath);
        /**
         * Now we have just removed that root key from the beggining of the namespace that was extra and also
         * took the Class name (not its file name) from the namespace.
         * And now we have got the address to the class file from the root of the Framework.
         * 
         * It means that with combining the (FILE_PATH) constant that gives us the absolute path to the Framework's root and having the address of the class name in the
         * Framework (that we it is) and adding the file extention to these things we can have the full path to that class that was called!
         */
        $filePath = FILE_PATH . $filePath . '.php';
        
        /*
         * We are checking to see whether that class exists or not,
         * If it exists then we are calling that class with using the (require_once) function.
         */
        if (is_readable($filePath)) {
            require_once $filePath;
            return;
        }else{
            global $registry;
            $registry->error->reportError("The Called Class File Does Not Exists! ({$filePath})", __LINE__, __METHOD__, false);
            return;
        }
        return;
    }

}