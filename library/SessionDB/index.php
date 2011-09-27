<?php
namespace root\library\SessionDB\index;

class SessionDB extends \root\application\baseModel\baseModel {
    private $sessionName = 'PHPSESSID', $DBTableName = 'session';

    public function __construct($unicodeQuery = false)
    {
        parent::__construct($unicodeQuery);
        
        /**
         * To tell the php parser that we want to use our own written session handler!
         */
        ini_set('session.save_handler', 'user');
        $this->endSession();
        register_shutdown_function('session_write_close');
    }
    
    /**
     * After initializing session (using class methods) for using session with our own handler its requires to call this mehtod
     * @return boolean TRUE on success | FALSE on failure.
     */
    public function start()
    {
        return $this->setHandlerSettings();
    }

    /**
     * With this method we are just setting our handler for session!
     * @return boolean TRUE on success | FALSE on failure .
     */
    private function setHandlerSettings()
    {
        return session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'garbageCollector'));
    }
    
    /**
     * This method is defining our read function for using it in our session handler. 
     * @param str $sessionId This is the session ID 
     * @return str empty if there is nothing, otherwise it will return the session data 
     */
    public function read($sessionId)
    {
        $sth = $this->prepare("SELECT `session_data` FROM {$this->DBTableName} WHERE `session_id`=:sessionId;UPDATE {$this->DBTableName} SET `access_time`=:accessTime  WHERE `session_id`=:sessionId;");
        $sth->bindValue(':accessTime', strftime('%Y-%m-%d %H:%M:%S'));
        $sth->bindValue(':sessionId', $sessionId, static::PARAM_STR);
        $sth->execute();
        $res = $sth->fetch();
        
        if(!empty($res['session_data']))
        {
            return $res['session_data'];
        }
        else
        {
            return '';
        }
    }
    
    /**
     * This method is defining our write function for using it in our session handler. 
     * @param str $sessionId
     * @param str $sessionData
     * @return boolean TRUE on success or FALSE on failure 
     */
    public function write($sessionId, $sessionData)
    {
        $sth = $this->prepare("INSERT INTO `{$this->DBTableName}`(`session_id`,`session_name`,`session_data`) VALUES(:session_id, :session_name, :session_data) ON DUPLICATE KEY UPDATE `session_data`=:session_data;");
        $sth->bindValue(':session_id', $sessionId);
        $sth->bindValue(':session_name', $this->sessionName);
        $sth->bindValue(':session_data', $sessionData);
        return $sth->execute();
    }
    
    /**
     * This method is defining our destroy function for using it in our session handler. 
     * @param str $sessionId
     * @return boolean TRUE on success or FALSE on failure .
     */
    public function destroy($sessionId) {
        $_SESSION = array();

        /**
         * Removing Session (cookie) from user side (client).
         */
        $sessCookieParms = session_get_cookie_params();
        setcookie($this->sessionName, NULL, 0, $sessCookieParms['path'], $sessCookieParms['domain'], $sessCookieParms['secure'], $sessCookieParms['httponly']);

        /**
         * Removing session from server.
         */
        return $this->removeRowInDb($sessionId);
    }
    
    /**
     * This method is defining our gc function for using it in our session handler. 
     * @param int $maxSessLifeTime , the life time of the session that are setted via session.gc_maxlifetime
     * @return int 1 , returning 1 means that every thing is Okay, even if there were nothing to be removed! 
     */
    public function garbageCollector($maxSessLifeTime)
    {
        $sth = $this->prepare("SELECT session_id, `access_time` FROM `{$this->DBTableName}`;");
        $sth->execute();
        
        foreach($sth->fetchAll() as $key)
        {
            $accessTime = strtotime($key['access_time']) + $maxSessLifeTime;
            
            if($accessTime < time())
            {
                $this->removeRowInDb($key['session_id']);
            }
        }
        return 1;
    }
    
    /**
     * This method is used to remove the sessions from the Database.
     * @param str $sessionId This is the session id
     * @return boolean True on success or false on failure.
     */
    private function removeRowInDb($sessionId)
    {
        $secondSth = $this->prepare("DELETE FROM `{$this->DBTableName}` WHERE session_id=:sessionId;");
        $secondSth->bindValue(':sessionId', $sessionId);
        return $secondSth->execute();
    }


    /**
     * This method is defining our open function for using it in our session handler.
     * @param str $savePath session save path address.
     * @param str $sessionName session name.
     * @return type 
     */
    public function open($savePath, $sessionName)
    {
        $this->sessionName = $sessionName;
        return 1;
    }

    /**
     * This method is defining our close function for using it in our session handler.
     * @return int 1, As its done its job successfuly instead of true 
     */
    public function close()
    {
        return 1;
    }

     /**
     * In php configuration file, session.auto_start might be enabled and it can cause problem ( Notice Error ).
     * And It's better to be sure that it's not started yet, by closing it at the beggining ot the way.
     */
    private function endSession()
    {
        if (ini_get('session.auto_start')) {
            session_write_close();
        }
        return;
    }

    /**
     * Using this method we can create our table in database (It's necessary to run it before using the class).
     * @return boolean TRUE in success (even if there is already table created it will return TRUE!), and FALSE in failure.
     */
    public function initCreateSessionTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS `$this->DBTableName` (
  `session_id` char(26) NOT NULL,
  `session_name` varchar(15) NOT NULL,
  `access_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session_data` mediumtext NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $sth = $this->prepare($query);
        return $sth->execute();
    }
    
    /**
     * This method can help us to define our session table name in our databade.
     * It's need do use it before start using the sessions. (before calling start() method)
     * @param type $tableName
     * @return int 1 , 1 is Instead of true.
     */
    public function initSetDBTableName($tableName)
    {
        $this->DBTableName = $tableName;
        return 1;
    }
    
    /**
     * This method can be used to set session maxx life time that after this time garbage collector will try to remove those expired sessions.
     * This must be set before start using the session (before calling start() method, otherwise it will be useless).
     * @param int $maxLifeTime , this must be in seconds
     * @return int|boolean , old value on success and FALSE on failure 
     */
    public function initSetMaxLifeTime($maxLifeTime)
    {
        return ini_set('session.gc_maxlifetime', $maxLifeTime);
    }
    
    /**
     * This method is used to set the probability of removing the session files via garbage collector.
     * For instance, if probability is set to 1 and divisor is set to 100 means that 1 in 100 (or in fact 1%) the garbage collector will try to find and remove the expired sessions,
     * These two setting both are put toghether because they usually will come toghether and there are relations between them! :) .
     * Just use this mehtod before start using the sessions (before calling the start() method).
     * @param int $probability
     * @param int $divisor
     * @return int 1, To show it was successful! Instead of true!
     */
    public function initGcProbabilityDivisor($probability, $divisor)
    {
        ini_set('session.gc_probability', $probability);
        ini_set('session.gc_divisor', $divisor);
        return 1;
    }
    
    /**
     * This method is used for when the security is really important and its better to use only cookies and not even session ID in URLs.
     * @return int 1, To show it was successful! Instead of true! 
     */
    public function initUseOnlyCookie() {
        /**
         * Make sure that this parameter is turned off.
         * It means that by default it will use cookie for sessions and not via URLs.
         */
        ini_set("session.use_trans_sid", 0);

        /**
         * For being sure that there is no way to use sessionS in URL it is a good idea to set this parameter off
         */
        ini_set("session.use_only_cookies", 0);
        
        return 1;
    }

    /**
     * We have got this method here, because our baseModel class is an abstract class and have an abstract method named index.
     * Just for avoiding any errors.
     */
    public function index() {}

}