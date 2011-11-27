<?php
require_once dirname(dirname(__FILE__)) . '/core/bootStrap.php';

$registry = \root\core\Registry\Registry::getInstance();
$registry->error = new \root\library\ErrorReporting\index\ErrorReporting;
$registry->loader = new \root\core\Loader\Loader;
$registry->request = new \root\core\Request\Request();

try 
{
    $registry->db = new \root\library\DatabaseConfig\index\DatabaseConfig($dbConfig);
    $registry->router = new \root\core\Router\Router();
} 
    catch (Exception $e) 
    {
        echo $e->getMessage();
    }
?>