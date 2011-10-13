<?php

class errorController extends baseController {
    
    protected function index(){}
    
    public function notFound()
    {
        $argArray = func_get_args();
        $arg = '---';
        
        if(isset($argArray[0]))
        {
            $arg = $argArray[0];
        }

        echo 'The Requested URL ( '.$arg.' ) Does Not Exist.';
    }
}