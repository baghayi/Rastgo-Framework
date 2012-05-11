<?php
use root\core\baseController\baseController,
    root\library\Pagination\index\Pagination;

class paginationController extends baseController
{
    public function index()
    {
        $page = new Pagination(self::$registry->request->getArgs());
        $page->initURLQueryStringName('page');
        $page->initTotalItemToBeShown(10);
        echo($page->getNewPageAddress($page->currentPageNumber()));
        echo '<br />';
        echo($page->getStyledPageNumbers('select * from session'));
        echo '<br />';
        var_dump($page->getContent("select * from session"));

        return;
    }
}
