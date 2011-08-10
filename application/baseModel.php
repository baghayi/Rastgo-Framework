<?php
abstract class baseModel extends PDO {

    public function __construct() {
        $dsn = DatabaseConfig::getDBType() . ':host=' . DatabaseConfig::getDBHost() . ';port=' . DatabaseConfig::getDBPort() . ';dbname=' . DatabaseConfig::getDBName();
        $username = DatabaseConfig::getDBUsername();
        $passwd = DatabaseConfig::getDBPassword();
        parent::__construct($dsn, $username, $passwd);
    }
    
    public abstract function index();

}