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
    
    public function register(){
        /**
         * To start using the Authentication class, first you need to make an object of this class.
         */
        $auth = new \root\library\Authentication\index\Authentication(true);
        /**
         * is used to set the name of the database table name with all operations will be done in it.
         */
        $auth->initDbTableName('auth');
        /**
         * used to set the name of the database column which the hash of password will be saved in it.
         */
        $auth->initDBHashColumnName('password_hash');
        /**
         * used to set the name of the database column which the hash of password should store there.
         */
        $auth->initDBSaltColumnName('password_salt');
        /**
         * This method is used to set the session_id column name in database which session_id should store in that column.
         */
        $auth->initDBSessionIdColumnName('session_id');
        /**
         * via this method you must set the name of the database column which stores user unique id that you can identify user with it (like: username, email, or other thing that you want)
         */
        $auth->initDBUserIdentifierColumnName('username');
        /**
         * Used to set a expiration time for the session cookie,
         * It's neccessary to use this method if you do not want your session cookie to be expired after your user close the browser.
         */
        var_dump($auth->initSessionCookieParams('day'));
        /**
         * This is used to login user via session cookie that is set by this mehtod: loginViaUserRawInfo()
         */
        var_dump($auth->loginViaCookie());
        /**
         * Login for the first time using this method (via html forms)
         */
        #var_dump($auth->loginViaUserRawInfo(array(
        #    'username' => 'hossein',
        #    'password' => 'hossein'
        #), true, true));
        /**
         * Create a salt and hashed password ussing this method
         */
        $password = $auth->makePassHashSalt('hossein');
        /**
         * insert the user information to database
         */
        $auth->registerNewUser(array('username' => 'hossein', 'email' => 'golam@gmail.com', 'password_hash' => $password['passHash'], 'password_salt' => $password['passSalt']));
        
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