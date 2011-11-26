<?php
/**
 * Database setting,
 * Put Your own information here:
 */
static $dbConfig = array();

/**
 * The name of the database (required).
 */
$dbConfig['name'] = 'test';

/**
 * The username of the database (required).
 */
$dbConfig['username'] = 'root';

/**
 * The password of the database (required).
 */
$dbConfig['password'] = 'demo';

/**
 * The host name of the database (optional).
 * Default is: '127.0.0.1'
 */
#$dbConfig['host'] = '127.0.0.1';

/**
 * The driver name of the database (optional).
 * Default is: 'mysql'
 */
#$dbConfig['driver'] = 'mysql';

/**
 * The port number of the database (optional).
 * Default is: 3306
 */
#$dbConfig['port'] = 3306;

/**
 * The variable will be given to the fourth parameter of the PDO class as options (optional).
 * And must be an array.
 * Default is: array()
 */
#$dbConfig['pdoOptions'] = array();