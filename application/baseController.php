<?php
abstract class baseController {
    
    public function __construct() {
        global $registry;
        $registry->view = new \root\application\View\View();
    }
    
    protected static function loadModel($loadMethod = false){
        global $registry;
        $registry->loader->loadModel($registry->request->getController());
        if($loadMethod === TRUE)
            return $registry->model->{$registry->request->getMethod()}();
    }

    public abstract function index();
}