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
        throw new \Exception('404 Error: The Controller File Does Not Exist!');
        return false;
    }
    
    private function instantiatingController(){
        require_once FILE_PATH . 'application' . DS . 'baseController.php';
        require_once $this->controllerAddress;
        $this->controllerInstance = new $this->controllerName($this->registry);
        if($this->controllerInstance){
            return true;
        }
        throw new \Exception('Controller Counld Be instantiated.');
    }
    
    private function checkingMethod(){
        if(!method_exists($this->controllerName, $this->method)){
            throw new \Exception('Entered Method ( '.__METHOD__ .' ) Cound Not Be Found');
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