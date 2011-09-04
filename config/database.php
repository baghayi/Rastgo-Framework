<?php
/**
 * Database setting,
 * Put Your own information here:
 */
$dbConfig = array();
$dbConfig['name'] = 'test';
$dbConfig['username'] = 'root';
$dbConfig['password'] = 'demo';
$dbConfig['host'] = 'localhost';
$dbConfig['driver'] = 'mysql';
$dbConfig['port'] = 3306;

final class DatabaseConfig {
    private $dbName, $dbUsername, $dbPassword, $dbHost, $dbDriver, $dbPort = 3306;
    
    public function __construct($dbConfigInfo) {
        global $registry;
        if(!is_array($dbConfigInfo)){
            $registry->error->reportError ('The Entered Parameter In Not Az Array! <br />It Must Be An Array!', __LINE__, __METHOD__, true);
            return FALSE;
        }
        elseif(is_array($dbConfigInfo)){
            $parameters = array('name'=>'setDBName','username'=>'setDBUsername','password'=>'setDBPassword','host'=>'setDBHost','driver'=>'setDBDriver','port'=>'setDBPort');
            foreach($dbConfigInfo as $key => $value){
                foreach ($parameters as $parmKey => $paramValue){
                    if($key === $parmKey){
                        $this->{$paramValue}($value);
                        continue;
                    }
                }
            }
            return TRUE;
        }
    }
    
    public function setDBName($dbName){
        $this->dbName = $dbName;
        return TRUE;
    }
    
    public function setDBUsername($dbUsername){
        $this->dbUsername = $dbUsername;
        return TRUE;
    }
    
    public function setDBPassword($dbPassword){
        $this->dbPassword = $dbPassword;
        return TRUE;
    }
    
    public function setDBHost($dbHost){
        $this->dbHost = $dbHost;
        return TRUE;
    }
    
    public function setDBDriver($dbDriver){
        $this->dbDriver = $dbDriver;
        return TRUE;
    }
    
    public function setDBPort($dbPort){
        $this->dbPort = $dbPort;
        return TRUE;
    }
    
    public function getDBName(){
        return $this->dbName;
        
    }
    
    public function getDBUsername(){
        return $this->dbUsername;
    }
    
    public function getDBPassword(){
        return $this->dbPassword;
    }
    
    public function getDBHost(){
        return $this->dbHost;
    }
    
    public function getDBDriver(){
        return $this->dbDriver;
    }
    
    public function getDBPort(){
        return $this->dbPort;
    }

}