<?php

class bcryptController extends baseController
{
    public function index()
    {
        $bcrypt = new root\library\Bcrypt\index\Bcrypt();
        $hash = $bcrypt->hash('mypassword');
        
        if($bcrypt->verify('mypassword', $hash))
        {
            echo 'Verified!';
            return;
        }
        
        echo 'Failure!';
        
        return;
    }
}