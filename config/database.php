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
$dbConfig['type'] = 'mysql';
$dbConfig['port'] = 3306;

final class DatabaseConfig {
    private $dbName, $dbUsername, $dbPassword, $dbHost, $dbType, $dbPort = 3306;
    
    public function __construct($dbConfigInfo) {
        if(!is_array($dbConfigInfo))
            \root\library\ErrorReporting\index\ErrorReporting::reportError ('The Entered Parameter In Not Az Array! <br />It Must Be An Array!', __LINE__, __METHOD__, true);
        elseif(is_array($dbConfigInfo)){
            $parameters = array('name'=>'setDBName','username'=>'setDBUsername','password'=>'setDBPassword','host'=>'setDBHost','type'=>'setDBType','port'=>'setDBPort');
            foreach($dbConfigInfo as $key => $value){
                foreach ($parameters as $parmKey => $paramValue){
                    if($key === $parmKey){
                        $this->{$paramValue}($value);
                        continue;
                    }
                }
            }
        }
    }
    
    public function setDBName($dbName){
        $this->dbName = $dbName;
    }
    
    public function setDBUsername($dbUsername){
        $this->dbUsername = $dbUsername;
    }
    
    public function setDBPassword($dbPassword){
        $this->dbPassword = $dbPassword;
    }
    
    public function setDBHost($dbHost){
        $this->dbHost = $dbHost;
    }
    
    public function setDBType($dbType){
        $this->dbType = $dbType;
    }
    
    public function setDBPort($dbPort){
        $this->dbPort = $dbPort;
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
    
    public function getDBType(){
        return $this->dbType;
    }
    
    public function getDBPort(){
        return $this->dbPort;
    }

}