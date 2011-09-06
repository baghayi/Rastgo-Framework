<?php
class indexModel extends baseModel {

    public function index(){
        $message = 'We Are Writing This Message From  Inside Of The Model File: => ' . __METHOD__;
        return $message;
    }
    
    public function cache(){
        return 'This Message is Cached!';
    }
    
}