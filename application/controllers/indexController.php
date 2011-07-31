<?php

class indexController extends baseController {

    public $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    public function index() {
        echo View::loadTemplate('index', array(
            'title' => 'Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!'
        ));
    }

    public function contact() {
        echo 'Congratulation, You Are In the Contact Page';
        echo '<br />';
        var_dump(func_get_args());
        echo '<br />';
    }

}