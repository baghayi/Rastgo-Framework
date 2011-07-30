<?php
class indexController extends baseController {

    
    public function index(){
        echo 'We are in the IndexController, 
            And laughing to you!
            Happy Birthday!';
        echo '<br />';
        var_dump(func_get_args());
        echo '<br />';
    }
    
    public function contact(){
        echo 'Congratulation, You Are In the Contact Page';
        echo '<br />';
        var_dump(func_get_args());
        echo '<br />';
    }

}