<?php
/**
 * Directory seperator constant
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Script real path, ( from server's root to script root folder )
 * like, c:\\ ... | /var/www/...
 */
define('FILE_PATH', get_magic_quotes_gpc() ? dirname(__FILE__) . DS : addslashes(dirname(__FILE__)) . DS);

/**
 * including config file
 */
require_once FILE_PATH . 'config' . DS . 'config.php';

/**
 * Including Database settings file,
 */
require_once FILE_PATH . 'config' . DS . 'database.php';

/**
 * IIS >= 6,
 * In IIS, It might have not been setted, then we can do ot manually!
 */
if (!isset($_SERVER['SERVER_NAME']))
    $_SERVER['SERVER_NAME'] = php_uname('n');

/**
 * Site address, 
 * Like: http://127.0.0.1/.../current_directory
 */
$url_value = (SCRIPT_ROOT_FOLDER_NAME == '') ? '' : SCRIPT_ROOT_FOLDER_NAME . '/';
define('URL', WEB_PROTOCOL . '://' . $_SERVER["SERVER_NAME"] . '/' . $url_value);
/**
 * Site Path to the template, 
 * For loading stylesheets, or javascripts, ..
 */
define('TEMPLATE_URL', URL . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME . DS);

/**
 * Full address of template in view folder,
 */
define('TEMPLATE_DIR_ADDRESS', FILE_PATH . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME);

/**
 * We are just including the Loader file that can been able to load or include other classes and files,
 * Then instantiating it
 */
require_once FILE_PATH . 'application' . DS . 'Loader.php';
Loader::setAutoLoader();