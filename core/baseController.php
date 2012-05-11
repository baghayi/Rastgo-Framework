<?php
namespace root\core\baseController;

abstract class baseController {

    public static $registry = NULL;

    public function __construct()
    {
        static::$registry->view = new \root\core\View\View();
        return;
    }
    
    protected function loadModel($loadDefaultMethod = false, $modelName = NULL, $modelMethod = NULL)
    {
        if($modelName !== NULL)
        {
            $modelToBeCalled = $modelName;
        }
        else{
            $modelToBeCalled = static::$registry->request->getController();
        }
        
        if($modelMethod !== NULL)
        {
            $modelMethodCalled = $modelMethod;
        }
        else{
            $modelMethodCalled = static::$registry->request->getMethod();
        }

        static::$registry->loader->loadModel($modelToBeCalled);
        
        if($loadDefaultMethod === TRUE)
        {
            if(method_exists($modelToBeCalled.'Model', $modelMethodCalled))
            {
                return static::$registry->model->{$modelMethodCalled}();
            }
            else{
                static::$registry->error->reportError('Requested Method Via The Controller Does Not Exists In The Model File.', __LINE__, __METHOD__,true);
                return;
            }
        }
        return 1;
    }

    protected abstract function index();
}