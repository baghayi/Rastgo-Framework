<?php
require_once 'bootStrap.php';

$registry = \root\application\Registry\Registry::getInstance();
$registry->loader = new Loader($registry);
$registry->request = new \root\application\Request\Request();
try{
    $registry->router = new \root\application\Router\Router($registry);
}catch(Exception $e){
    echo $e->getMessage();
}