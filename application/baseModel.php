<?php
class baseModel extends PDO {

    function __construct() {
        global $db_info;
        $dsn = $db_info['type'] . ':host=' . $db_info['host'] . ';port=' . $db_info['port'] . ';dbname=' . $db_info['name'];
        $username = $db_info['username'];
        $passwd = $db_info['password'];
        parent::__construct($dsn, $username, $passwd);
    }

}