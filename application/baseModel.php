<?php
namespace root\application\baseModel;

abstract class baseModel extends \PDO {

    public function __construct($unicodeQuery = false) {
        global $registry;
        
        $dsn = $registry->db->getDBDriver() . ':host=' . $registry->db->getDBHost() . ';port=' . $registry->db->getDBPort() . ';dbname=' . $registry->db->getDBName();
        $username = $registry->db->getDBUsername();
        $passwd = $registry->db->getDBPassword();
        parent::__construct($dsn, $username, $passwd);
        
        if($unicodeQuery === true)
        {
            $sth = $this->prepare('SET NAMES utf8');
            $sth->execute();
        }
        return;
    }
    
    public abstract function index();

}