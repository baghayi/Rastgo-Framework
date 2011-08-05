<?php
require_once 'bootStrap.php';

$registry = Registry::getInstance();
$registry->url = new Url();
try{
    $registry->router = new Router($registry);
}catch(Exception $e){
    echo $e->getMessage();
}