<?php
namespace root\application\Router;
class Router {
    private $controllerInstance, $controllerName, $controllerAddress;
    
    public function __construct() {
        $this->getController();
        $this->instantiatingController();
        $this->checkingMethod();
        $this->callingMethod();
    }
    
    private function getController(){
        global $registry;
        
        $controllerAddress = FILE_PATH . 'application' . DS . 'controllers' . DS . $registry->request->getController() . 'Controller.php';
        if(file_exists($controllerAddress) and is_readable($controllerAddress)){
            $this->controllerName = $registry->request->getController() . 'Controller';
            $this->controllerAddress = $controllerAddress;
            return true;
        }
        $registry->reportError('404 Error: The Controller File Does Not Exist!', __LINE__, __METHOD__, true);
        return false;
    }
    
    private function instantiatingController(){
        global $registry;
        require_once FILE_PATH . 'application' . DS . 'baseController.php';
        require_once $this->controllerAddress;
        $this->controllerInstance = new $this->controllerName();
        if($this->controllerInstance){
            return true;
        }
        $registry->reportError('Controller Counld Be instantiated.', __LINE__, __METHOD__, true);
    }
    
    private function checkingMethod(){
        global $registry;
        
        if(!method_exists($this->controllerName, $registry->request->getMethod())){
            $registry->reportError('Entered Method ( '. $registry->request->getMethod() .' ) Cound Not Be Found', __LINE__, __METHOD__, true);
        }
    }
    
    private function callingMethod(){
        global $registry;
        
        $args = $registry->request->getArgs();
        if(empty($args)){
            call_user_func(array($this->controllerName,$registry->request->getMethod()));
        }else{
            call_user_func_array(array($this->controllerName,$registry->request->getMethod()), $registry->request->getArgs());
        }
    }

}