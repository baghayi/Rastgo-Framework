<?php
require_once 'bootStrap.php';

$registry = \root\application\Registry\Registry::getInstance();
$registry->error = new \root\library\ErrorReporting\index\ErrorReporting;
$registry->loader = new \root\application\Loader\Loader;
$registry->request = new \root\application\Request\Request();

try {
    $registry->db = new \root\library\DatabaseConfig\index\DatabaseConfig($dbConfig);
    $registry->router = new \root\application\Router\Router();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>