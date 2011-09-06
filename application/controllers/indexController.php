<?php
class indexController extends baseController {
    
    public function index() {
        global $registry;
        
        $registry->view->renderTemplate(__FUNCTION__, array(
            'title' => 'Framework\'s Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!',
            'modelmessage' => static::loadModel(true)
        ));
        return;
    }
    
    public function cache(){
        global $registry;
        $registry->cache = new root\library\Cache\index\Cache;
        $registry->cache->setHashFileName(false);
        
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
            'title' => 'Framework\'s Main Title.',
            'content' => 'The Main Page Of the MVC-Based Frameword!',
            'modelmessage' => static::loadModel(true)
        ));
        echo $registry->cache->cacheBuffer('cache','cache',3);
    }
}