<?php
namespace root\application\Router;
class Router {
    private $controller, $method, $args,$controllerInstance;
    private $controllerName, $controllerAddress;
    public $registry;
    
    public function __construct(\root\application\Registry\Registry $registry) {
        $this->registry = $registry;
        $this->controller = $registry->request->getController();
        $this->method     = $registry->request->getMethod();
        $this->args       = $registry->request->getArgs();
        
        $this->getController();
        $this->instantiatingController();
        $this->checkingMethod();
        $this->callingMethod();
    }
    
    private function getController(){
        $controllerAddress = FILE_PATH . 'application' . DS . 'controllers' . DS . $this->controller . 'Controller.php';
        if(file_exists($controllerAddress) and is_readable($controllerAddress)){
            $this->controllerName = $this->controller . 'Controller';
            $this->controllerAddress = $controllerAddress;
            return true;
        }
        \root\library\ErrorReporting\index\ErrorReporting::reportError('404 Error: The Controller File Does Not Exist!', __LINE__, __METHOD__, true);
        return false;
    }
    
    private function instantiatingController(){
        require_once FILE_PATH . 'application' . DS . 'baseController.php';
        require_once $this->controllerAddress;
        $this->controllerInstance = new $this->controllerName($this->registry);
        if($this->controllerInstance){
            return true;
        }
        \root\library\ErrorReporting\index\ErrorReporting::reportError('Controller Counld Be instantiated.', __LINE__, __METHOD__, true);
    }
    
    private function checkingMethod(){
        if(!method_exists($this->controllerName, $this->method)){
            \root\library\ErrorReporting\index\ErrorReporting::reportError('Entered Method ( '. $this->registry->request->getMethod() .' ) Cound Not Be Found', __LINE__, __METHOD__, true);
        }
    }
    
    private function callingMethod(){
        if(empty($this->args)){
            call_user_func(array($this->controllerName,$this->method));
        }else{
            call_user_func_array(array($this->controllerName,$this->method), $this->args);
        }
    }

}