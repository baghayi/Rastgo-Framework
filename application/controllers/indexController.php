<?php
use root\core\baseController\baseController,
    \root\library\Translator\index\Translator;

class indexController extends baseController {

    public function index()
    {
        self::$registry->translation = new Translator();
        self::$registry->translation->loadLanguageFile('main');

        self::$registry->view->renderTemplate(__FUNCTION__, array(
            'title' => self::$registry->translation->translate('indexTitle'),
            'content' => self::$registry->translation->translate('indexContent'),
            'modelmessage' => $this->loadModel(true)
        ));
        return;
    }
        
}