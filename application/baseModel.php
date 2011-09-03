<?php
abstract class baseModel extends PDO {

    public function __construct() {
        global $registry;
        
        $dsn = $registry->db->getDBDriver() . ':host=' . $registry->db->getDBHost() . ';port=' . $registry->db->getDBPort() . ';dbname=' . $registry->db->getDBName();
        $username = $registry->db->getDBUsername();
        $passwd = $registry->db->getDBPassword();
        parent::__construct($dsn, $username, $passwd);
        return;
    }
    
    public abstract function index();

}