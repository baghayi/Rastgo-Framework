<?php
use root\core\baseController\baseController;

class indexController extends baseController {

    public function index()
    {
        self::$registry->view->renderTemplate(__FUNCTION__, array(
            'title' => 'Rastgo Framework',
            'content' => 'This is a demo page only to show that how this MVC-Based framework works.',
            'modelmessage' => $this->loadModel(true)
        ));
        return;
    }
        
}