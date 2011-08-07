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
 * Including Database settings file,
 */
require_once FILE_PATH . 'config' . DS . 'database.php';

/**
 * Site address, 
 * Like: http://127.0.0.1/.../current_directory
 */
define('SITE_PATH', WEB_PROTOCOL . '://'.$_SERVER["SERVER_NAME"].'/'.SCRIPT_ROOT_FOLDER_NAME.'/');

/**
 * Site Path to the template, 
 * For loading stylesheets, or javascripts, ..
 */
define('SITE_TEMPLATE_PATH', SITE_PATH  . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME . DS);

/**
 * Full address of template in view folder,
 */
define('TEMPLATE_DIR_ADDRESS',FILE_PATH . 'application' . DS . 'views' . DS . TEMPLATE_FOLDER_NAME);

/**
 * We are just including the Loader file that can been able to load or include other classes and files,
 * Then instantiating it
 */
require_once FILE_PATH . 'application' . DS . 'Loader.php';
Loader::setAutoLoader();