<?php

class indexController extends baseController {
    
    public function index() {
//        $usersList = array();
//        $contact = new mContact();
//        while($res = $contact->contact()){
//            $usersList[] =  $res['name'];
//        }
        
        View::renderTemplate(__FUNCTION__, array(
            'title' => 'Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!'
//            'usersList' => $usersList
        ));
        

    }

    public function contact() {
        echo 'Congratulation, You Are In the Contact Page';
        echo '<br />';
        var_dump(func_get_args());
        echo '<br />';
        
        /**
         * This bunch of code are just an example of calling and using the Model files
         */
        
//        $contact = new mContact();
//        while($res = $contact->contact()){
//            echo $res['name'];
//            echo '<br />';
//        }
    }

}