<?php
namespace root\library\DatabaseConfig\index;

final class DatabaseConfig {

    private 
            /**
             * Includes the name of the database.
             */
            $dbName,
            
            /**
             * Includes the username of the database which you want to connect to.
             */
            $dbUsername,
            
            /**
             * Includes the password of the database which you want to connect to.
             */
            $dbPassword,
            
            /**
             * Includes the host name of the database (the address of it).
             */
            $dbHost = '127.0.0.1',
            
            /**
             * Includes the driver, such as, mysql, mssql , ... .
             */
            $dbDriver = 'mysql',
            
            /**
             * Includes the port number of the database which you can connect to the server thorugh this port.
             */
            $dbPort = 3306,
            
            /**
             * Includes the options that will be given to the PDO class and it mnust be an array.
             */
            $pdoOptions = array(),
            
            /**
             * The name of the database configuration name, in the config directory.
             */
            $configFileName = 'database.ini';
            
    /**
     * This constructor method will be run after instantiation and will get the database configuration as az array.
     * The array keys are: name, username, password, host, driver, port, pdoOptions .
     * 
     * @global object $registry The object of the Registry Class.
     * @param array $dbConfigInfo The array that includes the database configuration, such as database name, database username, ... .
     * @return void
     */
    public function __construct() 
    {
        global $registry;
        $dbConfigInfo = $this->parseConfigFile();
        
        if (!is_array($dbConfigInfo)) 
        {
            $registry->error->reportError('The Entered Parameter Is Not An Array! <br />It Must Be An Array!', __LINE__, __METHOD__, true);
            return 0;
        }
        elseif (is_array($dbConfigInfo)) 
        {
            $parameters = array(
                'name' => 'dbName',
                'username' => 'dbUsername',
                'password' => 'dbPassword',
                'host' => 'dbHost',
                'driver' => 'dbDriver',
                'port' => 'dbPort',
                'pdoOptions' => 'pdoOptions'
                );
            
            foreach ($dbConfigInfo as $key => $value)
            {
                foreach ($parameters as $parmKey => $paramValue) 
                {
                    if ($key === $parmKey) 
                    {
                        $this->{$paramValue}($value);
                        continue;
                    }
                }
            }
            return;
        }
    }
    
    public function parseConfigFile()
    {
        $configFileAddress = FILE_PATH . 'config' . DS . $this->configFileName;
        
        return parse_ini_file($configFileAddress);
    }
    
    /**
     * This method lets us to define our database name.
     * @param string $dbName Database Name.
     * @return string The current value ( the new value if is defined ) will be returned.
     */
    public function dbName($dbName = NULL) 
    {
        if($dbName === NULL)
        {
            return $this->dbName;
        }
            
        return ($this->dbName = $dbName);
    }

    /**
     * This method lets us to define our database username.
     * @param string $dbUsername The database username.
     * @return string The current value ( the new value if is defined ) will be returned. 
     */
    public function dbUsername($dbUsername = NULL)
    {
        if($dbUsername === NULL)
        {
            return $this->dbUsername;
        }
        
        return ($this->dbUsername = $dbUsername);
    }

    /**
     * This method lets us to define our database password.
     * @param string $dbPassword The database password.
     * @return string The current value ( the new value if is defined ) will be returned.
     */
    public function dbPassword($dbPassword = NULL)
    {
        if($dbPassword === NULL)
        {
            return $this->dbPassword;
        }
        
        return ($this->dbPassword = $dbPassword);
    }

    /**
     * This method lets us to define our database host name.
     * @param string $dbHost The database host name (like, 127.0.0.1, localhost, ... ).
     * @return string The current value ( the new value if is defined ) will be returned.
     */
    public function dbHost($dbHost = NULL) 
    {
        if($dbHost === NULL)
        {
            return $this->dbHost;
        }
        
        return ($this->dbHost = $dbHost);
    }

    /**
     * This method lets us to define our database driver name.
     * @param string $dbDriver The database driver name (like: mysql, ... ).
     * @return string The current value ( the new value if is defined ) will be returned.
     */
    public function dbDriver($dbDriver = NULL) 
    {
        if($dbDriver === NULL)
        {
            return $this->dbDriver;
        }
        
        return ($this->dbDriver = $dbDriver);
    }

    /**
     * This method lets us to define our database port number.
     * @param int $dbPort The database port number.
     * @return string The current value ( the new value if is defined ) will be returned.
     */
    public function dbPort($dbPort = NULL) 
    {
        if($dbPort === NULL)
        {
            return (int)$this->dbPort;
        }
        
        return ($this->dbPort = (int)$dbPort);
    }    
    
    /**
     * This method lets us to define PDO's fourth parameter that in options for PDO and must be an array.
     * @global object $registry The object of the Registry class.
     * @param array $pdoOptions The PDO's fourth parameter that must be an array.
     * @return array The current value ( the new value if is defined ) will be returned.
     */
    public function pdoOptions($pdoOptions = NULL) 
    {
        global $registry;

        if($pdoOptions === NULL)
        {
            return $this->pdoOptions;
        }
        
        if(!is_array($pdoOptions))
        {
            $registry->error->reportError('The PDOOptions() first parameter must be an array!', __LINE__, __METHOD__, true);
            return 0;
        }
        
        return ($this->pdoOptions = $pdoOptions);
    }
}