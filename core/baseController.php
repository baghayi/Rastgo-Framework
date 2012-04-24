<?php
namespace root\core\baseController;

abstract class baseController {

    public $registry = NULL;

    public function __construct(\root\core\Registry\Registry $registry) {
        $this->registry = $registry;
        $this->registry->view = new \root\core\View\View();
        return;
    }
    
    protected function loadModel($loadDefaultMethod = false, $modelName = NULL, $modelMethod = NULL)
    {
        if($modelName !== NULL)
        {
            $modelToBeCalled = $modelName;
        }
        else{
            $modelToBeCalled = $this->registry->request->getController();
        }
        
        if($modelMethod !== NULL)
        {
            $modelMethodCalled = $modelMethod;
        }
        else{
            $modelMethodCalled = $this->registry->request->getMethod();
        }
            
        $this->registry->loader->loadModel($modelToBeCalled);
        
        if($loadDefaultMethod === TRUE)
        {
            if(method_exists($modelToBeCalled.'Model', $modelMethodCalled))
            {
                return $this->registry->model->{$modelMethodCalled}();
            }
            else{
                $this->registry->error->reportError('Requested Method Via The Controller Does Not Exists In The Model File.', __LINE__, __METHOD__,true);
                return;
            }
        }
        return 1;
    }

    protected abstract function index();
}