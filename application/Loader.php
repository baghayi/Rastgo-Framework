<?php
final class Loader {
    protected $registry;

    function __construct(root\application\Registry\Registry $registry) {
        $this->registry = $registry;
    }

    public function loadModel($ModleName) {
        $ModelCompleteName = $ModleName . 'Model';
        $ModelPath = FILE_PATH . 'application' . DS . 'models' . DS . $ModelCompleteName . '.php';

        if (file_exists($ModelPath)) {
            require_once FILE_PATH . 'application' . DS . 'baseModel.php';
            require_once $ModelPath;

            if (class_exists($ModelCompleteName))
                $this->registry->model = new $ModelCompleteName;
            else
                root\library\ErrorReporting\index\ErrorReporting::reportError ('Model Class Does Not Exists!', __LINE__, __METHOD__, true);
        }else {
            root\library\ErrorReporting\index\ErrorReporting::reportError('Model File Could Not Be Found!', __LINE__, __METHOD__, true);
        }
    }

    public static function setAutoLoader() {
        spl_autoload_register(array(__class__, 'autoLoader'));
    }

    public static function autoLoader($namespace) {

        $filePath = explode('\\', $namespace);
        $className = array_pop($filePath);
        array_shift($filePath);
        $filePath = implode(DS, $filePath);
        $filePath = FILE_PATH . $filePath . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }

}