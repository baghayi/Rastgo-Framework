<?php
use root\core\baseController\baseController,
    \root\library\Translator\index\Translator,
    root\library\Cache\index\Cache,
    root\library\Pagination\index\Pagination;

class indexController extends baseController {

    public function index() {
        $this->registry->translation = new Translator();
        $this->registry->translation->loadLanguageFile('main');

        $this->registry->view->renderTemplate(__FUNCTION__, array(
            'title' => $this->registry->translation->translate('indexTitle'),
            'content' => $this->registry->translation->translate('indexContent'),
            'modelmessage' => $this->loadModel(true)
        ));
        return;
    }
    
    public function cache(){
        $this->registry->cache = new Cache;
        $this->registry->cache->setHashFileName(false);
        $this->registry->translation = new Translator();
        $this->registry->translation->loadLanguageFile('main');
        
        /**
         * If the Cache file exists these bunch of codes will be excuted.
         */
        if($this->registry->cache->getCache('cache', 'cache')){
            echo $this->registry->cache->getCache('cache', 'cache');
            return;
        }
        /**
         * If the cache file does not exists then these belowe codes will be excuted.
         */
        $this->registry->cache->startBuffer();
        $this->registry->view->renderTemplate('index', array(
            'title' => $this->registry->translation->translate('indexTitle'),
            'content' => $this->registry->translation->translate('indexContent'),
            'modelmessage' => $this->loadModel(true)
        ));
        echo $this->registry->cache->cacheBuffer('cache', 'cache', 3);
        return;
    }
    
    
    
    public function pagination()
    {
        $page = new Pagination(func_get_args());
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