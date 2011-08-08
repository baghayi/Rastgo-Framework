<?php
abstract class baseController {
    public static $registry;
    
    public function __construct(\root\application\Registry\Registry $registy) {
        static::$registry = $registy;
        static::$registry->view = new \root\application\View\View();
    }
    
    protected static function loadModel($loadMethod = false){
        static::$registry->loader->loadModel(static::$registry->request->getController());
        if($loadMethod === TRUE)
            return static::$registry->model->{static::$registry->request->getMethod()}();
    }

    public abstract function index();
}