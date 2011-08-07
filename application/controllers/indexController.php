<?php

class indexController extends root\application\baseController\baseController {
    
    public function index() {
        \root\application\View\View::renderTemplate(__FUNCTION__, array(
            'title' => 'Framework\'s Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!',
            'modelmessage' => static::loadModel(true)
        ));
    }
}