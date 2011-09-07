<?php
class indexController extends baseController {
    
    public function index() {
        global $registry;
        $registry->translation = new \root\library\Translator\index\Translator();
        $registry->translation->loadLanguageFile('main');

        $registry->view->renderTemplate(__FUNCTION__, array(
            'title' => $registry->translation->translate('indexTitle'),
            'content' => $registry->translation->translate('indexContent'),
            'modelmessage' => static::loadModel(true)
        ));
        return;
    }
    
    public function cache(){
        global $registry;
        $registry->cache = new root\library\Cache\index\Cache;
        $registry->cache->setHashFileName(false);
        $registry->translation = new \root\library\Translator\index\Translator();
        $registry->translation->loadLanguageFile('main');
        
        /**
         * If the Cache file exists these bunch of codes will be excuted
         */
        if($registry->cache->getCache('cache','cache')){
            echo $registry->cache->getCache('cache','cache');
            return;
        }
        /**
         * If the cache file does not exists then these belowe codes will be excuted
         */
        $registry->cache->startBuffer();
        $registry->view->renderTemplate('index', array(
            'title' => $registry->translation->translate('indexTitle'),
            'content' => $registry->translation->translate('indexContent'),
            'modelmessage' => static::loadModel(true)
        ));
        echo $registry->cache->cacheBuffer('cache','cache',3);
    }
}