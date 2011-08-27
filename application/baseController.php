<?php
abstract class baseController {
    
    public function __construct() {
        global $registry;
        $registry->view = new \root\application\View\View();
        $registry->view->setBaseDir(TEMPLATE_DIR_ADDRESS);
    }
    
    protected static function loadModel($loadDefaultMethod = false, $modelName = null, $modelMethod = null){
        global $registry;
        if($modelName !== null)
            $modelToBeCalled = $modelName;
        else
            $modelToBeCalled = $registry->request->getController();
        
        if($modelMethod !== null)
            $modelMethodCalled = $modelMethod;
        else
            $modelMethodCalled = $registry->request->getMethod();
            
        $registry->loader->loadModel($modelToBeCalled);
        if($loadDefaultMethod === TRUE){
            if(method_exists($modelToBeCalled.'Model',$modelMethodCalled))
                return $registry->model->{$modelMethodCalled}();
            else
                $registry->reportError ('Requested Method Via The Controller Does Not Exists In The Model File.', __LINE__, __METHOD__,true);
                    
        }
    }

    public abstract function index();
}