<?php
class View extends Template {
    
    public function __construct() {
        template::setBaseDir(TEMPLATE_DIR_ADDRESS);
    }
    
}