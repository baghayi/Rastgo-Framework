<?php
/**
 * Script real path, ( from server's root to script root folder ) .
 * like, c:\\ ... | /var/www/... .
 */
define('FILE_PATH', get_magic_quotes_gpc() ? dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR : addslashes(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);

/**
 * including config file.
 */
require_once FILE_PATH . 'config' . DIRECTORY_SEPARATOR . 'config.php';

/**
 * IIS >= 6 ,
 * In IIS, It might have not been setted, then we can do ot manually!
 */
if (!isset($_SERVER['SERVER_NAME'])){
    $_SERVER['SERVER_NAME'] = php_uname('n');
}

/**
 * Site address,
 * Like: http://127.0.0.1/.../current_directory .
 */
$url_value = (SCRIPT_ROOT_FOLDER_NAME == '') ? '' : SCRIPT_ROOT_FOLDER_NAME . '/';
$portNumber = (Port_Number_In_URL) ? ':' .  $_SERVER['SERVER_PORT'] : '';
define('URL', WEB_PROTOCOL . '://' . $_SERVER["SERVER_NAME"] . $portNumber . '/' . $url_value);
/**
 * Site Path to the template, 
 * For loading stylesheets, or javascripts, ... .
 */
define('TEMPLATE_URL', URL . 'public/views/' . TEMPLATE_FOLDER_NAME . '/');

/**
 * We are just including the Loader file that
 * can been able to load or include other classes and files
 * then instantiating it.
 */
require_once FILE_PATH . 'core' . DIRECTORY_SEPARATOR . 'Loader.php';
\root\core\Loader\Loader::setAutoLoader();