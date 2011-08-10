<?php
/**
 * Database setting,
 * Put Your own information here:
 */
DatabaseConfig::setDBname('testt');
DatabaseConfig::setDBUsername('root');
DatabaseConfig::setDBPassword('demo');
DatabaseConfig::setDBHost('localhost');
DatabaseConfig::setDBType('mysql');
DatabaseConfig::setDBPort('3306');

final class DatabaseConfig {
    private static $dbName, $dbUsername, $dbPassword, $dbHost, $dbType, $dbPort = 3306;

    public static function setDBName($dbName){
        static::$dbName = $dbName;
    }
    
    public static function setDBUsername($dbUsername){
        static::$dbUsername = $dbUsername;
    }
    
    public static function setDBPassword($dbPassword){
        static::$dbPassword = $dbPassword;
    }
    
    public static function setDBHost($dbHost){
        static::$dbHost = $dbHost;
    }
    
    public static function setDBType($dbType){
        static::$dbType = $dbType;
    }
    
    public static function setDBPort($dbPort){
        static::$dbPort = $dbPort;
    }
    
    public static function getDBName(){
        return static::$dbName;
        
    }
    
    public static function getDBUsername(){
        return static::$dbUsername;
    }
    
    public static function getDBPassword(){
        return static::$dbPassword;
    }
    
    public static function getDBHost(){
        return static::$dbHost;
    }
    
    public static function getDBType(){
        return static::$dbType;
    }
    
    public static function getDBPort(){
        return static::$dbPort;
    }

}