<?php
namespace root\core\baseModel;

abstract class baseModel extends \PDO
{
    public static $registry;

    public function __construct($unicodeQuery = false)
    {
        $dsn = static::$registry->db->dbDriver() . ':host=' . static::$registry->db->dbHost() . ';port=' . static::$registry->db->dbPort() . ';dbname=' . static::$registry->db->dbName();

        parent::__construct($dsn, static::$registry->db->dbUsername(), static::$registry->db->dbPassword(), static::$registry->db->pdoOptions());
        
        if($unicodeQuery === true)
        {
            $sth = $this->query('SET NAMES utf8');
        }
        return;
    }

    public abstract function index();

}