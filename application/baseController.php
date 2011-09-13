<?php
abstract class baseController {
    
    public function __construct() {
        global $registry;
        $registry->view = new \root\application\View\View();
        return;
    }
    
    protected static function loadModel($loadDefaultMethod = false, $modelName = NULL, $modelMethod = NULL){
        global $registry;
        
        if($modelName !== NULL){
            $modelToBeCalled = $modelName;
        }
        else{
            $modelToBeCalled = $registry->request->getController();
        }
        
        if($modelMethod !== NULL){
            $modelMethodCalled = $modelMethod;
        }
        else{
            $modelMethodCalled = $registry->request->getMethod();
        }
            
        $registry->loader->loadModel($modelToBeCalled);
        
        if($loadDefaultMethod === TRUE){
            
            if(method_exists($modelToBeCalled.'Model', $modelMethodCalled)){
                return $registry->model->{$modelMethodCalled}();
            }
            else{
                $registry->error->reportError('Requested Method Via The Controller Does Not Exists In The Model File.', __LINE__, __METHOD__,true);
                return;
            }
        }
        return 1;
    }

    public abstract function index();
}