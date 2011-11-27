<?php
namespace root\application\Request;

final class Request {
    private $controller, $method, $args;
    private static $defaultController = 'index', $defaultMethod = 'index';

    function __construct() {
        /**
         * Taking the the URL and cleaning it with the current filder name and then,
         * We could been able to extract controller, method and arguments .
         */
        $url = array_filter(explode('/', $_SERVER['REQUEST_URI']));
        if (in_array(SCRIPT_ROOT_FOLDER_NAME, $url)) {
            array_shift($url);
        }
        
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
        return 1;
    }
    public static function sefDefaultMethod($method){
        self::$defaultMethod = $method;
        return 1;
    }

}