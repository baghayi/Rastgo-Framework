<?php
use root\core\baseController\baseController,
    root\library\Translator\index\Translator,
    root\library\Cache\index\Cache;

class cacheController extends baseController
{
    public function index()
    {
        static::$registry->cache = new Cache;
        static::$registry->cache->setHashFileName(false);
        static::$registry->translation = new Translator();
        static::$registry->translation->loadLanguageFile('main');

        /**
         * If the Cache file exists these bunch of codes will be excuted.
         */
        if(static::$registry->cache->getCache('cache', 'cache')){
            echo static::$registry->cache->getCache('cache', 'cache');
            return;
        }
        /**
         * If the cache file does not exists then these belowe codes will be excuted.
         */
        static::$registry->cache->startBuffer();
        static::$registry->view->renderTemplate('index', array(
            'title' => static::$registry->translation->translate('indexTitle'),
            'content' => static::$registry->translation->translate('indexContent'),
            'modelmessage' => $this->loadModel(true)
        ));
        echo static::$registry->cache->cacheBuffer('cache', 'cache', 3);
        return;
    }
}
