<?php
namespace root\core\baseModel;

abstract class baseModel extends \PDO {

    public $registry;

    public function connect($unicodeQuery = false)
    {
        $dsn = $this->registry->db->dbDriver() . ':host=' . $this->registry->db->dbHost() . ';port=' . $this->registry->db->dbPort() . ';dbname=' . $this->registry->db->dbName();

        parent::__construct($dsn, $this->registry->db->dbUsername(), $this->registry->db->dbPassword(), $this->registry->db->pdoOptions());
        
        if($unicodeQuery === true)
        {
            $sth = $this->query('SET NAMES utf8');
        }
        return;
    }
    
    public abstract function index();
    public function __construct(){}

}