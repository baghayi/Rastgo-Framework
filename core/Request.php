<?php
namespace root\core\Request;

final class Request {
    private $controller, $method, $args;
    private static $defaultController = Default_Controller, $defaultMethod = Default_Method;
    public static $enableRewrite = false;

    public function __construct() 
    {
        self::$enableRewrite = Enable_Rewrite;
        
        $queryString = $this->parseQueryString();
        $this->specifyingControllerMethodArguments($queryString);
        return;
    }
    
    /**
    * This method sets our controller if is not specified by user the default one will be used,
    * And sets our method (action) if is not specified by user the default one will be used,
    * And finally the Arguments as array will be set but if is not specified by user , an empty array() will be set.
    */
    private function specifyingControllerMethodArguments($queryString)
    {
         /**
         * Extracting the query string by forward-slash and then using them as controller , method (action) and arguments.
         */
        $url = array_filter(explode('/', $queryString));
        
        /**
         * Getting controller.
         */
        $this->controller = ($controller = array_shift($url)) ? $controller : self::$defaultController;
        
        /**
         * Getting Method.
         */
        $this->method = ($method = array_shift($url)) ? $method : self::$defaultMethod;
        
        /**
         * Getting All of the Arguments.
         */
        $this->args = isset($url[0])? $url : array();
        
        return;
    }
    
    /**
    * will return a strin if query string is set, like: $_GET['q'] .
    * otherwise false will be returned.
    */
    private function parseQueryString()
    {
        $queryString = isset($_GET['q']) ? $_GET['q'] : false;
        return $queryString;
    }
    
    public function getController(){
        return $this->controller;
    }
    
    public function getMethod(){
        return $this->method;
    }
    
    public function getArgs(){
        return $this->args;
    }
    
    public static function setDefaultController($controller){
        self::$defaultController = $controller;
        return;
    }
    public static function setDefaultMethod($method){
        self::$defaultMethod = $method;
        return;
    }
    
    public function go($controller = NULL, $method = NULL, $args = NULL, $returnAddress = false)
    {
        $controller = ($controller == NULL)? $this->getController(): $controller;
        $method = ($method == NULL)? $this->getMethod(): $method;
        $args = ($args == NULL)? $this->getArgs(): $args;
        
        switch(self::$enableRewrite){
            case TRUE:
                $newAddress = URL . "";
            break;
            case FALSE:
            default: 
                $newAddress = URL . "?q=";
            break;
        }
        
        if(is_array($args)){
            $newAddress .= $controller . '/' . $method . '/' . implode('/', $args);
        }elseif(is_string($args)){
            $newAddress .= $controller . '/' . $method . '/' . $args;
        }
        
        if($returnAddress === true){
            return $newAddress;
        }
        
        header("Location: ". $newAddress);
        exit;
    }

}
