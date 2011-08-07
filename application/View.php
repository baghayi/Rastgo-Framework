<?php
namespace root\application\View;
class View extends \root\library\Template\index\Template {
    
    public function __construct() {
        \root\library\Template\index\Template::setBaseDir(TEMPLATE_DIR_ADDRESS);
    }
    
}