<?php
class indexModel extends \root\core\baseModel\baseModel
{

    public function index()
    {
        return 'We Are Writing This Message From  Inside Of The Model File: => ' . __METHOD__;
    }
}