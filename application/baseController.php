<?php
abstract class baseController {
    protected $registry;
    
    public function __construct() {
        $this->registry = Registry::getInstance();
    }
    public abstract function index();

}