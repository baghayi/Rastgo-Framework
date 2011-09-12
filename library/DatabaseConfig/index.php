<?php
namespace root\library\DatabaseConfig\index;

final class DatabaseConfig {

    private $dbName, $dbUsername, $dbPassword, $dbHost, $dbDriver, $dbPort = 3306;

    public function __construct($dbConfigInfo) {
        global $registry;
        if (!is_array($dbConfigInfo)) {
            $registry->error->reportError('The Entered Parameter In Not Az Array! <br />It Must Be An Array!', __LINE__, __METHOD__, true);
            return 0;
        } elseif (is_array($dbConfigInfo)) {
            $parameters = array('name' => 'setDBName', 'username' => 'setDBUsername', 'password' => 'setDBPassword', 'host' => 'setDBHost', 'driver' => 'setDBDriver', 'port' => 'setDBPort');
            foreach ($dbConfigInfo as $key => $value) {
                foreach ($parameters as $parmKey => $paramValue) {
                    if ($key === $parmKey) {
                        $this->{$paramValue}($value);
                        continue;
                    }
                }
            }
            return 1;
        }
    }

    public function setDBName($dbName) {
        $this->dbName = $dbName;
        return 1;
    }

    public function setDBUsername($dbUsername) {
        $this->dbUsername = $dbUsername;
        return 1;
    }

    public function setDBPassword($dbPassword) {
        $this->dbPassword = $dbPassword;
        return 1;
    }

    public function setDBHost($dbHost) {
        $this->dbHost = $dbHost;
        return 1;
    }

    public function setDBDriver($dbDriver) {
        $this->dbDriver = $dbDriver;
        return 1;
    }

    public function setDBPort($dbPort) {
        $this->dbPort = $dbPort;
        return 1;
    }

    public function getDBName() {
        return $this->dbName;
    }

    public function getDBUsername() {
        return $this->dbUsername;
    }

    public function getDBPassword() {
        return $this->dbPassword;
    }

    public function getDBHost() {
        return $this->dbHost;
    }

    public function getDBDriver() {
        return $this->dbDriver;
    }

    public function getDBPort() {
        return $this->dbPort;
    }

}