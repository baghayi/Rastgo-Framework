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
         * If the Cache file exists these bunch of codes will be excuted.
         */
        if($registry->cache->getCache('cache', 'cache')){
            echo $registry->cache->getCache('cache', 'cache');
            return;
        }
        /**
         * If the cache file does not exists then these belowe codes will be excuted.
         */
        $registry->cache->startBuffer();
        $registry->view->renderTemplate('index', array(
            'title' => $registry->translation->translate('indexTitle'),
            'content' => $registry->translation->translate('indexContent'),
            'modelmessage' => static::loadModel(true)
        ));
        echo $registry->cache->cacheBuffer('cache', 'cache', 3);
        return;
    }
    
    
    
    public function pagination()
    {
        $page = new root\library\Pagination\index\Pagination(func_get_args());
        $page->initURLQueryStringName('page');
        $page->initTotalItemToBeShown(10);
        echo($page->getNewPageAddress($page->currentPageNumber()));
        echo '<br />';
        echo($page->getStyledPageNumbers('select * from session'));
        echo '<br />';
        var_dump($page->getContent("select * from session"));
        
        return;
    }
        
}