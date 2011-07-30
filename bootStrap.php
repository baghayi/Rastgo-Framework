<?php
require_once 'config.php';

/**
 * Directory seperator constant
 */
define('DS',DIRECTORY_SEPARATOR);

/**
 * Script real path, ( from server's root to script root folder )
 * like, c:\\ ... | /var/www/...
 */
define('FILE_PATH',  realpath(dirname(__FILE__)) . DS);

/**
 * Site address, 
 * Like: http://127.0.0.1/.../current_directory
 */
define('SITE_PATH', WEB_PROTOCOL . '://'.$_SERVER["SERVER_NAME"].'/'.SCRIPT_ROOT_FOLDER_NAME.'/');

/**
 * Loding libraries, and MVC required files,
 */
require_once FILE_PATH . 'application' . DS . 'registry.php';
require_once FILE_PATH . 'application' . DS . 'request.php';
require_once FILE_PATH . 'application' . DS . 'router.php';
require_once FILE_PATH . 'application' . DS . 'baseController.php';


$registry = Registry::getInstance();
$registry->request = new Request();
try{
    $registry->router = new Router($registry);
}catch(Exception $e){
    echo $e->getMessage();
}
