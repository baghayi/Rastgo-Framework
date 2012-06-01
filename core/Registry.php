<?php
namespace root\core\Registry;

final class Registry {
    private $registry = array();
    private static $instance = NULL;
    
    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function __set($var, $parm){
        $this->registry[$var] = $parm;
        return;
    }
    
    public function __get($var){
        return $this->registry[$var]; 
    }

	 public function __unset($var){
        unset($this->registry[$var]);
    }

	 public function __isset($var){
        return isset($this->registry[$name]);
    }

    private function __construct() {}

	 private function __clone() {}
}