<?php
/**
 * This code checks to see if xdebug_disable function exists or not, if so then will run it to disable xdebug.
 * As its known, Xdebug will generate long error messages that is not necessary in testing and rather having a line of error message which can indicate the root of problem will do.
 */
if(function_exists('xdebug_disable'))
    xdebug_disable();

//this line includes composer autoloader file. then this file (bootStrap) will be used by phpunit to map the RastgoFramework classes.
require_once 'vendor/autoload.php';
