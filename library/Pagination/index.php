<?php
namespace root\library\Pagination\index;

class Pagination extends \root\application\baseModel\baseModel {
    
    private $firstPageName = 'First Page', $lastPageName = 'Last Page', $nextPageName = 'Next Page', $previousPageName = 'Previous Page', $leftToRightLanguage = true, $urlQueryStringName = NULL, $totalItemsToBeShown = NULL;
    private $totalPages = NULL, $URLQueryString, $currentPageNumber = NULL, $currentPageNumberState = false, $dbQuery;
    
    /**
     * Our construct method that will be run before other methods.
     * @param @param array $URLQueryString The value that comes from func_get_args() via the controller method.
     * @param type $unicodeQuery if it's set t True then this query ('SET NAMES utf8') will be run before other querys, and it is good to use it in utf8 languages such as Farsi, ... .
     * @return void 
     */
    public function __construct($URLQueryString, $unicodeQuery = false) {
        parent::__construct($unicodeQuery);
        $this->URLQueryString = $URLQueryString;
        
        return;
    }
    
    /**
     * Through this method we can get our content related to our page number (if its not set then the default page number is 1), and it will return the content (rows) that we had wanted (in our query) as an array.
     * @global object $registry The object of the Registry class.
     * @param string $dbQuery The (database) query which will be used to get the rows fromn the database.
     * @return mixed 0 on failure, an array that includes the content on success.
     */
    public function getContent($dbQuery = NULL)
    {
        global $registry;
        
        if($this->totalItemsToBeShown == NULL)
        {
            $registry->error->reportError('Total Items that must be shown in a page is not defined yet, You can do it by using initTotalItemToBeShown() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        if($dbQuery != NULL)
        {
            $queryFirstPart = $dbQuery;
        }
        elseif($this->dbQuery != NULL)
        {
            $queryFirstPart = $this->dbQuery;
        }
        else
        {
            $registry->error->reportError('Query is not set, You can set it using the method\'s first parameter, ir using initDBQuery() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        $limitPartOfQuery = ' LIMIT '. (($this->totalItemsToBeShown * $this->currentPageNumber()) - $this->totalItemsToBeShown) . ' , ' . $this->totalItemsToBeShown . ';';
        
        if(false === ($sth = $this->query($queryFirstPart . $limitPartOfQuery)))
        {
            $errorMessage = $this->errorInfo();
            $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false);
            return 0;
        }
        
        return $sth->fetchAll();
    }
    
    /**
     * Through this method we can define our (database) query and then we won't need to define it it the other method's parameter like these methods: getContent(), getPageNumbers(), getStyledPageNumbers() .
     * @param type $dbQuery The (database) query to use it selecting rows from the database.
     * @return void
     */
    public function initDBQuery($dbQuery)
    {
        $this->dbQuery = $dbQuery;
        return;
    }
    
    /**
     * This method tells us which pages we have got and will bring those page numbers as an array, like array(1, 2, 3, 4, ... ) .
     * Then we can use those numbers to make our own pagination style.
     * By the way, we can use other method to make our method for professional, like currentPageNumber(), ... .
     * @global object $registry The object of the Registry class.
     * @param string $dbQuery The (database) query which will be used to get rows from the database.
     * @return mixed 0 on failure that it there is no items in database it will return 0 or if $totalItemsToBeShown property was now set then it will return 0, otherwise it will bring as array that include our page numbers.
     */
    public function getPageNumbers($dbQuery = NULL)
    {
        global $registry;
        
        if($dbQuery != NULL)
        {
            $query = $dbQuery;
        }
        elseif($this->dbQuery != NULL)
        {
            $query = $this->dbQuery;
        }
        else
        {
            $registry->error->reportError('Query is not set, You can set it using the method\'s first parameter, ir using initDBQuery() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        if(false === ($sth = $this->query($query)))
        {
            $errorMessage = $this->errorInfo();
            $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false);
            return 0;
        }
            
        $totalSelectedItems = count($sth->fetchAll());
        
        $this->autoRedirectToFirstpage();
        
        /**
         * If in the database are not any items to be shown then return 0, and stop right now.
         */
        if($totalSelectedItems === 0)
        {
            return 0;
        }
        
        if($this->totalItemsToBeShown === NULL)
        {
            $registry->error->reportError('Total Items that must be shown in a page is not defined yet, You can do it by using initTotalItemToBeShown() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        $this->totalPages = (int)ceil($totalSelectedItems / $this->totalItemsToBeShown);
        $this->autoRedirectToLastPage();

        /**
         * We have got an array that includes the page numbers.
         */
        $pageNumbers = array();
        for($i = 1; $i <= $this->totalPages; $i++)
        {
            $pageNumbers[] = $i;
        }
        
        return $pageNumbers;
    }
    
    /**
     * This mrthod uses the getPageNumbers() method to get the result, and make a styled pagination in html but css and will return a string that includes these codes.
     * Aslo in some of the html code are defined some IDs to been able to style them with css to make it more beautiful.
     * This method is for crazy programmers that do not want to make it by themselves! :) .
     * @param string $dbQuery The (database) query which will be used to get rows from the database.
     * @return mixed 0 on failure (all explanations of getPageNumbers() method includes), string includes our styled pagination html code in success.
     */
    public function getStyledPageNumbers($dbQuery = NULL)
    {
        $pageNumsArray = $this->getPageNumbers($dbQuery);
        
        if($pageNumsArray === 0)
        {
            return 0;
        }
        
        $currentPageNum = $this->currentPageNumber();
                
        $finalString = "\n<ul id=\"pagenumbersid\">\n";
        
        if($currentPageNum !== 1)
        {
            $finalString .= "<li id=\"firstpageid\"><a href=\"{$this->getNewPageAddress(1)}\">{$this->firstPageName}</a></li>\n";
            $finalString .= "<li id=\"previouspageid\"><a href=\"{$this->getNewPageAddress($currentPageNum - 1)}\">{$this->previousPageName}</a></li>\n";
        }
        
        $decreaseTwoPoint = $currentPageNum-2;
        /**
         * Because it can not be lower that 1 and the 1 is the lowest number then we will check whether it's lower that 1 or not.
         * If its lower than then we can set it to 1 manually.
         */
        if($decreaseTwoPoint < 1)
        {
            $decreaseTwoPoint = 1;
        }
        
        $increaseTwoPoint = $currentPageNum+2;
        /**
         * Because it can not be more bigger than our total page numbers then we will check it to see whether it's bigger that our total page numbers or not.
         * If it's bigger then we will set it to the total page numbers manually.
         */
        if($increaseTwoPoint > $this->totalPages)
        {
            $increaseTwoPoint = $this->totalPages;
        }
        
        for($i = $decreaseTwoPoint; $i <= $increaseTwoPoint; $i++)
        {
            if($i == $currentPageNum)
            {
                $finalString .= "<li>{$i}</li>\n";
            }
            else
            {
                $finalString .= "<li><a href=\"{$this->getNewPageAddress($i)}\">{$i}</a></li>\n";
            }
        }
        
        if($currentPageNum !== $this->totalPages)
        {
            $finalString .= "<li id=\"nextpageid\"><a href=\"{$this->getNewPageAddress($currentPageNum + 1)}\">{$this->nextPageName}</a></li>\n";
            $finalString .= "<li id=\"lastpageid\"><a href=\"{$this->getNewPageAddress($this->totalPages)}\">{$this->lastPageName}</a></li>\n";
        }
        
        $finalString .= '</ul>';
        
        return $finalString;
    }
    

    /**
     * Through this method we can find the new address (URL) of the new page number that we want.
     * @global object $registry The object of the Registry Class.
     * @param int $newPageNumber The new page number that we want to make an address for that page.
     * @return mixed 0 on failure and an exception will be thrown too, the new page's address if everything is okay.
     */
    public function getNewPageAddress($newPageNumber)
    {
        global $registry;
        
        if($this->urlQueryStringName === NULL)
        {
            $registry->error->reportError('The Url Query String for Pagination is not defined yet, You can do it by using initURLQueryStringName() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        /**
         * If the query string for pagination is not added yet, then we will add is and the new page number too.
         */
        if(false === ($pageQueryStringNamePosition = array_search($this->urlQueryStringName, $this->URLQueryString)))
        {
            $this->URLQueryString[] = $this->urlQueryStringName;
            $this->URLQueryString[] = $newPageNumber;
        }
        /**
         * But if its defined we will change the pagenumber, or add the page number.
         */
        else
        {
            $this->URLQueryString[++$pageQueryStringNamePosition] = $newPageNumber;
        }
        
        /**
         * Making the url and then returning it back.
         */
        $finalQueryString = implode('/', $this->URLQueryString);
        $newAddress = URL . $registry->request->getController() . '/' . $registry->request->getMethod() . '/' . $finalQueryString . '/';
        
        return $newAddress;
    }
    
    /**
     * This method lets us to redirect user to the new page number that we want user to go there.
     * @global object $registry The object of the Registry Class.
     * @param int $newPageNumber The new page number which we want to go there.
     * @return int 0 on failure with an exception will be thrown, 1 if we are already in the requested page, Nothing if it will do its job well.
     */
    public function redirectToNewPage($newPageNumber)
    {
        global $registry;
        
        if($this->urlQueryStringName === NULL)
        {
            $registry->error->reportError('The Url Query String for Pagination is not defined yet, You can do it by using initURLQueryStringName() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        /**
         * If we are in the new page already then do not continue.
         */
        if($this->currentPageNumber() == $newPageNumber)
        {
            return 1;
        }
        
        /**
         * If the query string for pagination is not added yet, then we will add is and the new page number too.
         */
        if(false === ($pageQueryStringNamePosition = array_search($this->urlQueryStringName, $this->URLQueryString)))
        {
            $this->URLQueryString[] = $this->urlQueryStringName;
            $this->URLQueryString[] = $newPageNumber;
        }
        /**
         * But if its defined we will change the pagenumber, or add the page number.
         */
        else
        {
            $this->URLQueryString[++$pageQueryStringNamePosition] = $newPageNumber;
        }
        
        /**
         * Making the url and then redirect to the new address.
         */
        $finalQueryString = implode('/', $this->URLQueryString);
        $newAddress = URL . $registry->request->getController() . '/' . $registry->request->getMethod() . '/' . $finalQueryString . '/';
        header("Location: $newAddress");
        exit;
        
        return;
    }
    
    /**
     * If the query string is added but the number not (is empty) it will redirect to first page, or if its set to 0 then it will redirect to page 1 (because we have not got any page number in 0).
     * @global object $registry The object of the Registry class.
     * @return mixed 0 on failure,  Nothing if the condition is not true, redirection if the condition is true.
     */
    private function autoRedirectToFirstpage()
    {
        global $registry;
        
        if($this->urlQueryStringName === NULL)
        {
            $registry->error->reportError('The Url Query String for Pagination is not defined yet, You can do it by using initURLQueryStringName() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        if(false !== ($pageQueryStringNamePosition = array_search($this->urlQueryStringName, $this->URLQueryString)))
        {
            if(!isset($this->URLQueryString[++$pageQueryStringNamePosition]) or !is_numeric($this->URLQueryString[$pageQueryStringNamePosition]) or ($this->URLQueryString[$pageQueryStringNamePosition] <= 0))
            {
                $this->URLQueryString[$pageQueryStringNamePosition] = 1;
                $finalQueryString = implode('/', $this->URLQueryString);
                $newAddress = URL . $registry->request->getController() . '/' . $registry->request->getMethod() . '/' . $finalQueryString . '/';
                header("Location: $newAddress");
                exit;
            }
        }
        return;   
    }
    
    /**
     * If the inserted page number is more than the total (last page number) numbers that we have got then this method will be started and redirect the user to the last page that we have got.
     * @global object $registry The object of the Registry class.
     * @return mixed 0 on failure,  Nothing if the condition is not true, redirection if the condition is true.
     */
    private function autoRedirectToLastPage()
    {
        global $registry;
        
        if($this->urlQueryStringName === NULL)
        {
            $registry->error->reportError('The Url Query String for Pagination is not defined yet, You can do it by using initURLQueryStringName() method.', __LINE__, __METHOD__, true);
            return 0;
        }
        
        if($this->currentPageNumber() > $this->totalPages)
        {
            $pageQueryStringNamePosition = array_search($this->urlQueryStringName, $this->URLQueryString);
            $this->URLQueryString[++$pageQueryStringNamePosition] = $this->totalPages;
            $finalQueryString = implode('/', $this->URLQueryString);
            $newAddress = URL . $registry->request->getController() . '/' . $registry->request->getMethod() . '/' . $finalQueryString . '/';
            header("Location: $newAddress");
            exit;
        }
        return;
    }
    
    /**
     * This method lets us to know which page we are now,
     * If its not defined yet then the 1 will be returned as a value,
     * If its defined and the page number defined too then the actual number will be returned,
     * If just the query string defined but the page number is not defined then 0 as false will be returned, like for example( http://www.domain.com/index/index/page/   => incorrect) that it must had been this one, like for example( http://www.domain.com/index/index/page/56/   => correct),
     * If its defined but the value is not a number then 0 will be returned as false,,
     * If the query string is not defined using this method (initURLQueryStringName()) then 0 will be returned and also an exception will be thrown ( for instance, the query instance can be 'page', or 'p', or ... ).
     * @global object $registry The instance of the Registry Class.
     * @return int 0 on failure, 1 if the page number is not defined in URL (the query its self and it's value), or the actual number that is defined in url.
     */
    public function currentPageNumber()
    {
        $this->autoRedirectToFirstpage();

        if($this->currentPageNumberState === true)
        {
            return $this->currentPageNumber;
        }

        /**
         * If it's not defined yet then the user actualy are in the first page, then return 1 as a result.
         */
        if(false === ($queryAnalysisResult = array_search($this->urlQueryStringName, $this->URLQueryString)))
        {
            $this->currentPageNumberState = TRUE;
            $this->currentPageNumber = 1;
            return 1;
        }
        
        $pageNumber = $this->URLQueryString[++$queryAnalysisResult];
        
        /**
         * In paginations the page number must be a Number.
         * If the value is not a number then something is wrong, may be someone wants to hack the system!
         * The the 0 will be returned as false.
         */
        if(!is_numeric($pageNumber))
        {
            return 0;
        }
        
        $this->currentPageNumberState = TRUE;
        $this->currentPageNumber = (int)$pageNumber;
        return (int)$pageNumber;
    }
    
    /**
     * This method lets us to define that how many items we want to be shown in page, and others to go to another page/pages.
     * For instance, in forums and in a topic, the 10 posts are shown in a page and the others go to the next page to be shown, and this methd lets us to change/set this matter.
     * @param type $value The number which those items will be shown.
     * @return void
     */
    public function initTotalItemToBeShown($value) 
    {
        $this->totalItemsToBeShown = (int)$value;
        return;
    }
    
    /**
     * This method lets us to set/change the url query string name that by it we will know that which page we are at the moment.
     * Some people sets it 'page', some like it to be 'p' , etc.
     * Like: http://www.ourdomain.org/page/22/  or  http://www.domain.org/p/23/ (instead of http://ourdomain.org/?page=22 or http://www.domain.org/?p=23).
     * @param type $value The url query name (page, p, ...).
     * @return void
     */
    public function initURLQueryStringName($value) 
    {
        $this->urlQueryStringName = $value;
        return;
    }
    
    /**
     * Through this method we can define if our web page is for left to right users or not.
     * By inserting true if its left to right (ltr) language that we are using, or false if its right to left (rtl) language that we are using.
     * @param type $value True or false should be the value.
     * @return void
     */
    public function initLeftToRightLanguage($value) 
    {
        $this->leftToRightLanguage = (boolean)$value;
        return;
    }

    /**
     * Through this method it's possible to set or change the first page link's name.
     * @param type $firstPageName The first page link's name.
     * @return void
     */
    public function initFirstPageName($firstPageName) 
    {
        $this->firstPageName = $firstPageName;
        return;
    }
    
    /**
     * Through this method it's possible to set or change the last page link's name.
     * @param type $lastPageNam The last page link's name.
     * @return void
     */
    public function initLastPageName($lastPageNam) 
    {
        $this->lastPageName = $lastPageNam;
        return;
    }
    
    /**
     * Through this method it's possible to set or change the next page link's name.
     * @param type $nextPageName The next link's name.
     * @return void
     */
    public function initNextPageName($nextPageName) 
    {
        $this->nextPageName = $nextPageName;
        return;
    }
    
    /**
     * Through this method it's possible to set or change the previous page link's name.
     * @param type $previousPageName The previous link's name.
     * @return void
     */
    public function initPreviousPageName($previousPageName) 
    {
        $this->previousPageName = $previousPageName;
        return;
    }
    
    public function index(){}
}