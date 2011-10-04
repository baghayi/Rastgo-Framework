<?php
namespace root\library\Authentication\index;

class Authentication extends \root\application\baseModel\baseModel {
    private $sessionInstance = NULL, $dbTableName = 'authentication', $pepper = '', $dbHashColumnName = 'pass_hash', $dbSaltColumnName = 'pass_salt', $userIdentifier, $dbUserIdentifierColumnName = NULL, $sessionId, $dbSessionIdColumnName = 'session_id';
    
    public function __construct($unicodeQuery = false) 
    {
        parent::__construct($unicodeQuery);
        $this->sessionInstance = new \root\library\SessionDB\index\SessionDB();
        /**
         * For security reasons it's better only allow cookies to been able to have the sessions and nothing esle (like URLs)
         */
        $this->sessionInstance->initUseOnlyCookie();
        $this->sessionInstance->start();
    }
    
    /**
     * Through this method we can check to see it there is any valid session cookie or not, and if it exists and is valid we can let user to come in (log in).
     * @global object $registry Object of Registry class.
     * @return int 1 on success that if user (session) is valid, or 0 on failure that if user (session) is not valid.
     */
    public function loginViaCookie()
    {
        session_commit();
        session_start();
        global $registry;
        
        if($this->dbUserIdentifierColumnName === NULL)
        {
            $registry->error->reportError('The "dbUserIdentifierColumnName" property is not set, You can set it by using this method: initDBUserIdentifierColumnName() ', __LINE__, __METHOD__, true, 'authentication');
            return 0;
        }

        if(isset($_SESSION['userIdentifier']) and ($_SESSION['verified'] === true))
        {
            $sth = $this->prepare("SELECT `{$this->dbSessionIdColumnName}` FROM `{$this->dbTableName}` WHERE `{$this->dbUserIdentifierColumnName}`=:userIdentifier LIMIT 1;");
            $sth->bindValue(':userIdentifier', $_SESSION['userIdentifier']);
            
            if(FALSE === $sth->execute())
            {
                $errorMessage = $sth->errorInfo();
                $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false, 'authentication');
                return 0;
            }
            
            $sessionId = $sth->fetch(static::FETCH_ASSOC);
            
            if($sessionId[$this->dbSessionIdColumnName] !== session_id())
            {
                $registry->error->reportError("The user session id was not matched with the session id inside of the database, then the user ({$_SESSION['userIdentifier']} was not allowed to log in.) ", __LINE__, __METHOD__, false, 'authentication');
                session_destroy();
                return 0;
            }
            
            if(isset($_SESSION['userIP']) and ($_SESSION['userIP'] !== $_SERVER['REMOTE_ADDR']))
            {
                $registry->error->reportError("The user IP was not matched with the user IP of session, then the user ({$_SESSION['userIdentifier']} was not allowed to log in.) ", __LINE__, __METHOD__, false, 'authentication');
                session_destroy();
                return 0;
            }
            
            if(isset($_SESSION['userBrowser']) and ($_SESSION['userBrowser'] !== $_SERVER['HTTP_USER_AGENT']))
            {
                $registry->error->reportError("The user browser type was not matched with the user browser type of session, then the user ({$_SESSION['userIdentifier']} was not allowed to log in.) ", __LINE__, __METHOD__, false, 'authentication');
                session_destroy();
                return 0;
            }
            
            return 1;
        }
        return 0;
    }
    
    /**
     * Through this mehtod we are able to log in user to the system and set a valid session cookie to the user via login html forms.
     * @global object $registry It's the object of the Registry class.
     * @param array $loginData The first parameter must be a unique thing to identify user by it (like username, email , etc) that the database column name must be the array's key and it's value must be that unique id (username, email, or ...)!
     * And also the second parameter must be user's password, that the array key must be the database column name and the array value must be password itself.
     * @param boolean $limitByIP If its set true then the user session will be sensitive to user IP while login through session cookie.
     * @param boolean $limitByUserBrowser If its set true then the user session will be sensitive to user browser type while login through session cookie.
     * @return int 1 on success if log in was successfully done otherwise 0 will be returned for failure. 
     */
    public function loginViaUserRawInfo($loginData, $limitByIP = FALSE, $limitByUserBrowser = FALSE)
    {
        global $registry;
        $this->dbUserIdentifierColumnName = key($loginData);
        $this->userIdentifier = $loginData[$this->dbUserIdentifierColumnName];
        
        /**
         * The first parameter must be an array, therfore if it's not an array or if it's empty then stop there and return 0 .
         */
        if(!is_array($loginData) or empty($loginData))
        {
            return 0;
        }
        
        /**
         * If the user identifier does not exist then stop right there and return 0 .
         */
        if($this->userIdentifierExists(array($this->dbUserIdentifierColumnName => $this->userIdentifier)) === 0)
        {
            return 0;
        }
        
        /**
         * - The first parameter must be an array!
         * - It's first element must be something unique that we can identify user with it, such as username, or email (if it's using as a username / (instead of username infact)), etc ,
         *   That the key must be database column name and the value must be its value.
         * - The second element must be user raw password. (The key must be the database column and the value part must be its password)
         */
        $sth = $this->prepare("SELECT `{$this->dbHashColumnName}`, `{$this->dbSaltColumnName}` FROM {$this->dbTableName} WHERE `{$this->dbUserIdentifierColumnName}`=:username;");
        $sth->bindValue(':username', $this->userIdentifier);
        
        if(false === $sth->execute())
        {
            $errorMessage = $sth->errorInfo();
            $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false, 'authentication');
            return 0;
        }
        
        $result = $sth->fetch();
        next($loginData);
        $rawPassword = $loginData[key($loginData)];
        
        if($this->makePassHashSalt($rawPassword, $result[$this->dbSaltColumnName], $result[$this->dbHashColumnName]))        
        {
            $this->setSession($this->userIdentifier, $limitByIP , $limitByUserBrowser);
            
            $this->updateDBSessionId($this->sessionId, $this->dbUserIdentifierColumnName, $this->userIdentifier);
            
            return 1;
        }
        
        return 0;
    }
    
    /**
     * This method lets us to know that whether the user identifier like username, email, etc (that are somthing unique to identify the specific user by it) does exist or not.
     * @global object $registry The object of Registry class.
     * @param array $userIdentifier It must be an array and must have only a key and it's value (and not any more).
     * @return int 1 if the user identifier does exist or 0 if it does not exist.
     */
    public function userIdentifierExists($userIdentifier)
    {
        global $registry;
        
        if(!is_array($userIdentifier) or empty($userIdentifier))
        {
            $registry->error->reportError('The First Parameter Must Be An Array, And Also It Must Not Be Empty!', __LINE__, __METHOD__, true, 'authentication');
            return 0;
        }
        
        $columnName = key($userIdentifier);
        $sth = $this->prepare("SELECT `{$columnName}` FROM `{$this->dbTableName}` WHERE `{$columnName}`=:userIdentifier LIMIT 1;");
        $sth->bindValue(':userIdentifier', $userIdentifier[key($userIdentifier)]);
        
        if(false === $sth->execute())
        {
            $errorMessage = $sth->errorInfo();
            $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false, 'authentication');
            return 0;
        }
        
        if(false === $sth->fetch(static::FETCH_ASSOC))
        {
            return 0;
        }
        
        return 1;
    }
    
    /**
     * This method lets us to define user information in a session to make it possible to keep user loged in.
     * @param str $userIdentifier User Identifier
     * @return 1 To show it's done well. 
     */
    private function setSession($userIdentifier, $limitByIP = FALSE, $limitByUserBrowser = FALSE)
    {
        session_start();
        
        /**
         * It's a good approach to regenerate a new session id in each login time.
         */
        session_regenerate_id();
        
        $this->sessionId = session_id();
        $_SESSION['userIdentifier'] = $userIdentifier;
        $_SESSION['verified'] = true;
        
        if($limitByIP == TRUE)
        {
            $_SESSION['userIP'] = $_SERVER['REMOTE_ADDR'];
        }
        
        if($limitByUserBrowser == TRUE)
        {
            $_SESSION['userBrowser'] = $_SERVER['HTTP_USER_AGENT'];
        }

        return 1;
    }
    
    /**
     * This method sets/updates the session id to its column in database.
     * @global object $registry The object of Registry class.
     * @param str $sessionId Session ID.
     * @param str $userIdentifierColumnName The user identifier column name in database (the column name).
     * @param str $userIdentifier The user identifier code.
     * @return int 1 on success or 0 on failure.
     */
    private function updateDBSessionId($sessionId, $userIdentifierColumnName, $userIdentifier) 
    {
        global $registry;
        
        $sth = $this->prepare("UPDATE {$this->dbTableName} SET {$this->dbSessionIdColumnName}=:sessionId WHERE `{$userIdentifierColumnName}`=:userIdentifier;");
        $sth->bindValue(':sessionId', $sessionId);
        $sth->bindValue(':userIdentifier', $userIdentifier);
        
        if(false === $sth->execute())
        {
            $errorMessage = $sth->errorInfo();
            $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false, 'authentication');
            return 0;
        }
        
        return 1;
    }
    
    /**
     * Via this method its possible to register a new user that can make a random salt (for security) and a hash using that salt and raw password (and even with a pepper that can be defined through this method: setPepper() ),
     * And also,
     * Its possible to check the inserted raw password with its salt/hash in database to check the validity of the inserted password for the right user!
     * @param mixed $rawPassword The password that a user was inserted it via login page!
     * @param str $passSalt A salt that can be defined by user whether to create a hash using with that salt OR to use it for third parameter to check the hashes
     * @param str $checkHash The hash of password that is stored in database.
     * @return mixed (will return salt/hash if the third parameter is not setted.) otherwise it will return 1 on success and or will return 0 on failure in comparison the hash of password.
     */
    public function makePassHashSalt($rawPassword, $passSalt = NULL, $checkHash = NULL)
    {
        $password = array();
        
        if($passSalt === NULL)
        {
            /**
             * A random Salt.
             * Usually when a user wants to sign up.
             */
            $password['passSalt'] = md5(rand());
        }else
        {
            /**
             * The salt are comming directly from the user side.
             * It can be setted by retrieving it from the database and can be used when the user wants to login!
             */
            $password['passSalt'] = $passSalt;
        }
        
        /**
         * A hashed password of raw password using above salt and also a pepper that are defined by user.
         * To make it more secure.
         */
        $password['passHash'] = md5($rawPassword . $password['passSalt'] . $this->pepper);
        
        /**
         * If this parameter is setted then instead of returning the salt and or hash,
         * It will just check to see whether the password's hash that comes from database is equal with the hash of password that are inserted via the first parameter,
         * And finally, it will return 1 if its equal and its right, or it will retrun 0 if its not equal. 
         */
        if($checkHash !== NULL)
        {
            if($password['passHash'] === $checkHash)
            {
                return 1;
            }else
            {
                return 0;
            }
        }
        
        /**
         * Returning the value that includes the password salt and also a hash of password.
         */
        return $password;
    }
    

    /**
     * As a second salt (pepper) to use it in hashin the password just for more security!
     * @param str $pepper Like a second salt for password.
     * @return int To show Its Done, As a true result. 
     */
    public function initPepper($pepper) 
    {
        $this->pepper = $pepper;
        return 1;
    }
    
    /**
     * Through this method we can set our database password hash column name.
     * By default its: pass_hash .
     * @param str $columnName The column name of the password hash in database.
     * @return int 1 To show its done successfully.
     */
    public function initDBHashColumnName($columnName)
    {
        $this->dbHashColumnName = $columnName;
        return 1;
    }
    
    /**
     * Through this method we can set the expiration time, domain address, ... to session cookie, if you do not use method then the default values in server will be used. .
     * Be aware that use this method before using the this method: loginViaUserRawInfo() otherwise it won't work as you expected.
     * @param mixed $lifeTimeParam This gets that value that session cookie will be valid in that time (its in second), and also you can use these strings ('day' for one day, 'week' for one week, 'month' for one month, and finally 'year' for one year) instead of inserting number in second (if you want to use thid method then its neccessary to insert a value to this parameter).
     * @param str $pathParam The path of where you want cookie to work and not other places (example: folders), (if you do not define it will use the server settings by defualt).
     * @param str $domainParam The domain address that will be set in session cookie (if you do not define it will use the server settings by defualt).
     * @param boolean $secureParam If you want to use secure connectin (https) you can set it true and if not false (if you do not define it will use the server settings by defualt).
     * @param boolean $httponlyParam If you want to use httponly you can set it true and if not false (if you do not define it will use the server settings by defualt).
     * @return int To show it's well done. 
     */
    public function initSessionCookieParams($lifeTimeParam, $pathParam = NULL, $domainParam = NULL, $secureParam = NULL, $httponlyParam = NULL) 
    {
        $defaultSessionCookieParms = session_get_cookie_params();
        $lifeTime = $defaultSessionCookieParms['lifetime'];
        $path = $defaultSessionCookieParms['path'];
        $domain = $defaultSessionCookieParms['domain'];
        $secure = $defaultSessionCookieParms['secure'];
        $httponly = $defaultSessionCookieParms['httponly'];
        
            switch ($lifeTimeParam)
            {
                case 'day':
                    $lifeTime = 86400;
                    break;
                case 'week':
                    $lifeTime = 86400 * 7;
                    break;
                case 'month':
                    $lifeTime = 86400 * 7 * 4;
                    break;
                case 'year':
                    $lifeTime = 86400 * 7 * 4 * 12;
                    break;
                default:
                    $lifeTime = $lifeTimeParam;
            }
            
            if($pathParam !== NULL)
            {
                $path = $pathParam;
            }
            
            if($domainParam !== NULL)
            {
                $domain = $domainParam;
            }
            
            if($secureParam !== NULL)
            {
                $secure = $secureParam;
            }
            
            if($httponlyParam !== NULL)
            {
                $httponly = $httponlyParam;
            }
            
            session_set_cookie_params($lifeTime, $path, $domain, $secure, $httponly);
            
            return 1;
    }
    
    /**
     * Through this method we can set our user identifier column name in database (like username, email, etc) that is unique and is used to specifying the user from others.
     * @param str $columnName The database column name of the user identifier (like username, email , etc) that are unique to specify the user.
     * @return int 1 To show it's well done. 
     */
    public function initDBUserIdentifierColumnName($columnName) 
    {
        $this->dbUserIdentifierColumnName = $columnName;
        return 1;
    }
    
    /**
     * Through this method we can set our database password salt column name.
     * By default its: pass_salt . 
     * @param str $columnName The column name of the password salt in database.
     * @return int 1 To show its done successfully.
     */
    public function initDBSaltColumnName($columnName) 
    {
        $this->dbSaltColumnName = $columnName;
        return 1;
    }
    
    /**
     * Throught this method its possible to submit our data to our database using prepared statements (Just for the array values not the array key).
     * @global object $registry Its the instance of the Registry Class.
     * @param array $data An array that includes our database information, key as table's column name and the value for its bvalue
     * @return int 0 on failure or 1 in success.
     */
    public function registerNewUser($data)
    {
        global $registry;
        
        /**
         * To be sure that the first parameter of method is an array and is not empty.
         */
        if(!is_array($data))
        {
            $registry->error->reportError('The value of the first parameter is not an array! It must be an array!', __LINE__, __METHOD__, true, 'authentication');
            return;
        }
        elseif(empty($data))
        {
            $registry->error->reportError('The value of the first parameter is Empty! At least it must have a value (a key and and its value)!', __LINE__, __METHOD__, true, 'authentication');
            return;
        }
        
        $columnNames = '';
        foreach($data as $key => $value)
        {
            $columnNames .= $key . ', ';
        }
        
        $sth = $this->prepare("INSERT INTO `{$this->dbTableName}`(".rtrim($columnNames, ', ').") VALUES(". rtrim(str_repeat(' ? , ', count($data)),', ') .");");
        
        $values = array();
        foreach ($data as $key => $value)
         {
            $values[] = $value;
        }
        
        if (false === $sth->execute($values))
         {
             $errorMessage = $sth->errorInfo();
             $registry->error->reportError($errorMessage[2], __LINE__, __METHOD__, false, 'authentication');
             return 0;
         }
        return 1;
    }
    
    /**
     * Thorugh this method we can change the Database Table name which is gonna save our authentication information
     * @param type $tableName Database Table Name
     * @return int 1 
     */
    public function initDbTableName($tableName) 
    {
        $this->dbTableName = $tableName;
        return 1;
    }
    
    /**
     * Through this method it's possible to introduce our session id column name insode of our database table to this class.
     * @param str $columnName The column name of the session Id in database.
     * @return int 1 , To show it's done well. 
     */
    public function initDBSessionIdColumnName($columnName) 
    {
        $this->dbSessionIdColumnName = $columnName;
        return 1;
    }
    
    /**
     * This method is used to logOut the user from system.
     * @param str $redirectTo the addrss of page which to be there after Loging out.
     */
    public function logOut($redirectTo = NULL)
    {
        session_destroy();
        
        if($redirectTo !== NULL)
        {
            header("Location: {$redirectTo}");
        }
    }
    

    public function index(){}
}