<?php
final class Loader {
    protected $registry;
    
    function __construct(Registry $registry) {
        $this->registry = $registry;
    }
    
    public function loadModel($ModleName){
        $ModelCompleteName = $ModleName . 'Model';
        $ModelPath = FILE_PATH . 'application' . DS . 'models' . DS . $ModelCompleteName . '.php';
        
        if(file_exists($ModelPath)){
            require_once $ModelPath;
            
                if(class_exists($ModelCompleteName))
                    $this->registry->model = new $ModelCompleteName;
                
                else
                    throw new Exception('Model Class Does Not Exists!');
                
        }else{
            throw new Exception('Model File Could Not Be Found!');
        }
    }

}