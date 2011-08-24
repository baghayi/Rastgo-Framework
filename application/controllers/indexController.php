<?php
class indexController extends baseController {
    
    public function index() {
        global $registry;
        
        $registry->view->renderTemplate(__FUNCTION__, array(
            'title' => 'Framework\'s Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!',
            'modelmessage' => static::loadModel(true)
        ));
    }
}