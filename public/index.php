<?php
require_once dirname(dirname(__FILE__)) . '/core/bootStrap.php';
$registry = \root\core\Registry\Registry::getInstance();
\root\core\Loader\Loader::setAutoLoader($registry);

    try
    {
        $registry->lib = \root\core\LibraryController\LibraryController::globalizeObject();
        $registry->loader = new \root\core\Loader\Loader;
        $registry->request = new \root\core\Request\Request();
        $registry->router = new \root\core\Router\Router();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }