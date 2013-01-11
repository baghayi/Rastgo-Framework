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
    
    /**
     * Using this method we can redirect user to other pages (controller, methods) which is defined in this framework (internal link).
     * Or We can get a link for those controller, methods.
     * With third parameter of this method we can pass arguments to methods (A similar way of using _GET).
     * @param  mixed  $controller    Controller name, or null to select the current controller.
     * @param  mixed  $method        Method name, or null to select the current method.
     * @param  mixed  $args          Array which its elements should be string and can not have keys and only array value, or String can be passed as well rather than array.
     * @param  boolean $returnAddress Specifies whether to sent the Redirect header to return back a make link as string.
     * @return string|Void                 Depends on the forth ($returnAddress) parameter.
     */
    public function go($controller = NULL, $method = NULL, $args = NULL, $returnAddress = false)
    {
        $controller = ($controller === NULL)? $this->getController(): $controller;
        $method = ($method === NULL)? $this->getMethod(): $method;
        $args = ($args === NULL)? $this->getArgs(): $args;
        
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
