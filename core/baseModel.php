<?php
namespace root\application\baseModel;

abstract class baseModel extends \PDO {

    public function __construct($unicodeQuery = false) {
        global $registry;
        
        $dsn = $registry->db->dbDriver() . ':host=' . $registry->db->dbHost() . ';port=' . $registry->db->dbPort() . ';dbname=' . $registry->db->dbName();

        parent::__construct($dsn, $registry->db->dbUsername(), $registry->db->dbPassword(), $registry->db->pdoOptions());
        
        if($unicodeQuery === true)
        {
            $sth = $this->query('SET NAMES utf8');
        }
        return;
    }
    
    public abstract function index();

}