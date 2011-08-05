<?php
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
 * including config file
 */
require_once FILE_PATH . 'config' . DS . 'config.php';

/**
 * Site address, 
 * Like: http://127.0.0.1/.../current_directory
 */
define('SITE_PATH', WEB_PROTOCOL . '://'.$_SERVER["SERVER_NAME"].'/'.SCRIPT_ROOT_FOLDER_NAME.'/');

/**
 * Site Path to the template, 
 * For loading stylesheets, or javascripts, ..
 */
define('TEMPLATE_SITE_PATH', SITE_PATH  . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME . DS);

/**
 * Full address of template in view folder,
 */
define('TEMPLATE_DIR_ADDRESS',FILE_PATH . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME);

/**
 * Loding libraries, and MVC required files,
 */
require_once FILE_PATH . 'application' . DS . 'registry.php';
require_once FILE_PATH . 'application' . DS . 'request.php';
require_once FILE_PATH . 'application' . DS . 'router.php';
require_once FILE_PATH . 'application' . DS . 'baseController.php';
require_once FILE_PATH . 'library'     . DS . 'template.class.php';
require_once FILE_PATH . 'application' . DS . 'view.php';


$registry = Registry::getInstance();
$registry->request = new Request();
try{
    $registry->router = new Router($registry);
}catch(Exception $e){
    echo $e->getMessage();
}