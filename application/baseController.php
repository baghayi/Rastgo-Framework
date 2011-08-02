<?php
abstract class baseController {
    public $registry;
    
    public function __construct(Registry $registy) {
        $this->registry = new $registy;
        $this->registry->view = new View();
    }

    public abstract function index();
}