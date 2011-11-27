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
    
    private function __construct() {}

}