<?php
namespace root\core\Router;

class Router 
{
    private $controllerInstance, $controllerName, $controllerAddress;
    
    public function __construct() {
        $this->getController();
        $this->instantiatingController();
        $this->checkingMethod();
        $this->callingMethod();
        return;
    }
    
    private function getController(){
        global $registry;
        
        $controllerAddress = FILE_PATH . 'application' . DS . 'controllers' . DS . $registry->request->getController() . 'Controller.php';
        
        if(file_exists($controllerAddress) and is_readable($controllerAddress))
        {
            $this->controllerName = $registry->request->getController() . 'Controller';
            $this->controllerAddress = $controllerAddress;
            return 1;
        }

        header("Location: " . URL . 'error/notFound/Controller/');
        exit;
    }
    
    private function instantiatingController(){
        global $registry;
        require_once $this->controllerAddress;
        $this->controllerInstance = new $this->controllerName();
        
        if(is_a($this->controllerInstance, $this->controllerName)){
            return 1;
        }
        
        $registry->error->reportError('Controller Counld Not Be instantiated.', __LINE__, __METHOD__, true);
        return;
    }
    
    private function checkingMethod(){
        global $registry;
        
        if(!in_array($registry->request->getMethod(), get_class_methods($this->controllerName)))
        {
            header("Location: " . URL . 'error/notFound/Method/');
            exit;
        }
        
        if(!method_exists($this->controllerName, $registry->request->getMethod()))
        {
            header("Location: " . URL . 'error/notFound/Method/');
            exit;
        }
        
        return 1;
    }
    
    private function callingMethod(){
        global $registry;
        
        $args = $registry->request->getArgs();
        
        if(empty($args)){
            call_user_func(array($this->controllerName, $registry->request->getMethod()));
            return 1;
        }else{
            call_user_func_array(array($this->controllerName, $registry->request->getMethod()), $registry->request->getArgs());
            return 1;
        }
    }

}