<?php
namespace root\application\Request;

class Request {
    private $controller,$method,$args;

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
         * Getting controller,
         */
        $this->controller = ($controller = array_shift($url)) ? $controller : 'index';
        
        /**
         * Getting Method.
         */
        $this->method = ($method = array_shift($url)) ? $method : 'index';
        
        /**
         * Getting All of the Arguments,
         */
        $this->args = isset($url[0])? $url : array();
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

}