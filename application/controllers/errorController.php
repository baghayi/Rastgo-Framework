<?php
use root\core\baseController\baseController;

class errorController extends baseController
{
    protected function index(){}
    
    public function notFound()
    {
        $argArray = self::$registry->request->getArgs();
        $arg = '---';
        
        if(isset($argArray[0]))
        {
            $arg = $argArray[0];
        }

        echo 'The Requested URL ( '.$arg.' ) Does Not Exist.';
    }
}