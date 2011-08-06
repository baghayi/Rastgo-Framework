<?php
require_once 'bootStrap.php';

$registry = Registry::getInstance();
$registry->request = new Request();
$registry->loader = new Loader($registry);
try{
    $registry->router = new Router($registry);
}catch(Exception $e){
    echo $e->getMessage();
}