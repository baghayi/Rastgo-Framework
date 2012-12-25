<?php
use root\library\Authentication\index\Authentication,
root\core\baseController\baseController;

class authenticationController extends baseController
{
    /**
     * A sample password to use it throughout of the class to demonstrate how does this Library (Authentication) works.
     */
    const samplePassword = 'aBigSecurePassword';
    /**
     * A sample bcrypted password (aove password hass) to use it throughout of the class to demonstrate how does this Library (Authentication) works.
     */
    const aBcryptedHashOfsamplePassword = '$2a$12$j/VL35/SRCHhrB.zAps5b.fKRBuwQYhH0bA9I3tBLfKPQkBRB3mP6';

    /**
     * Will show a link for each methods and with some information in the URL to use them.
     * @return void
     */
    public function index()
    {
        $chekingTheUser = self::$registry->request->go("authentication", "chekingTheUser", "username:hossein", true);
        $registerMD5 = self::$registry->request->go("authentication", "registerMD5", "username:hossein/password:yourpasswordhere/email:yourmail@gmail.com", true);
        $verifyMD5Hash = self::$registry->request->go("authentication", "verifyMD5Hash", "username:hossein/password:yourpasswordhere/email:yourmail@gmail.com", true);
        $registerBcrypt = self::$registry->request->go("authentication", "registerBcrypt", "username:hossein/password:yourpasswordhere/email:yourmail@gmail.com", true);
        $verifyBcryptHash = self::$registry->request->go("authentication", "verifyBcryptHash", "username:hossein/password:yourpasswordhere/email:yourmail@gmail.com", true);
        $logUseInUsingBcryptMethod = self::$registry->request->go("authentication", "logUseInUsingBcryptMethod", "", true);
        $mainMethod = self::$registry->request->go("authentication", "mainMethod", "", true);
        echo <<<HTML
            <ul>
                <li><a href="{$chekingTheUser}/" alt="">chekingTheUser()</a></li>
                <li><a href="{$registerMD5}/" alt="">registerMD5()</a></li>
                <li><a href="{$verifyMD5Hash}/" alt="">verifyMD5Hash()</a></li>
                <li><a href="{$registerBcrypt}/" alt="">registerBcrypt() </a></li>
                <li><a href="{$verifyBcryptHash}/" alt="">verifyBcryptHash()</a></li>
                <li><a href="{$logUseInUsingBcryptMethod}/" alt="">logUseInUsingBcryptMethod()</a></li>
                <li><a href="{$mainMethod}" alt="">mainMethod()</a></li>
            </ul>
HTML;
        return;
    }

    /**
     * This method shows infact most of the Authentication methods in action and how they work and some information about them , ... .
     * @return void
     */
    public function mainMethod()
    {
        /**
         * To start using the Authentication class, first you need to make an object of this class.
         */
        $auth = new Authentication(true);
        /**
         * is used to set the name of the database table name with all operations will be done in it.
         */
        $auth->initDbTableName('auth');
        /**
         * used to set the name of the database column which the hash of password will be saved in it.
         */
        $auth->initDBHashColumnName('password_hash');
        /**
         * used to set the name of the database column which the hash of password should store there.
         */
        $auth->initDBSaltColumnName('password_salt');
        /**
         * This method is used to set the session_id column name in database which session_id should store in that column.
         */
        $auth->initDBSessionIdColumnName('session_id');
        /**
         * via this method you must set the name of the database column which stores user unique id that you can identify user with it (like: username, email, or other thing that you want)
         */
        $auth->initDBUserIdentifierColumnName('username');
        /**
         * Used to set a expiration time for the session cookie,
         * It's neccessary to use this method if you do not want your session cookie to be expired after your user close the browser.
         */
        $auth->initSessionCookieParams('day');
        /**
         * This is used to login user via session cookie that is set by this mehtod: loginViaUserRawInfo()
         */
        var_dump($auth->loginViaCookie());

        
        /**
         * Login for the first time using this method (via html forms)
         */
        #var_dump($auth->loginViaUserRawInfo(array(
        #    'username' => 'hossein',
        #    'password' => 'hossein'
        #), true, true));
        /**
         * Create a salt and hashed password ussing this method
         */
        $password = $auth->makePassHashMD5('hossein');
        /**
         * insert the user information to database
         */
        $auth->registerNewUser(array('username' => 'hossein', 'email' => 'golam@gmail.com', 'password_hash' => $password['passHash'], 'password_salt' => $password['passSalt']));

        return;
    }

    /**
     * A sample example method which demonstrates how to use bcrypte method to log user in with raw information
     *      that may come from a login page or whatever.
     */
    public function logUseInUsingBcryptMethod()
    {
        /*
          Database Users Table Structure:
          
          CREATE TABLE `users` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `username` varchar(20) NOT NULL,
             `password` varchar(80) NOT NULL,
             `session_id` varchar(80) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`)
            ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1
        */
        $auth = new Authentication();
        //echo $obj->makePassHashBcrypt(self::samplePassword, self::aBcryptedHashOfsamplePassword);

        /**
         * is used to set the name of the database table name with all operations will be done in it.
         */
        $auth->initDbTableName('users');
        /**
         * used to set the name of the database column which the hash of password will be saved in it.
         */
        $auth->initDBHashColumnName('password');
        /**
         * This method is used to set the session_id column name in database which session_id should store in that column.
         */
        $auth->initDBSessionIdColumnName('session_id');
        /**
         * via this method you must set the name of the database column which stores user unique id that you can identify user with it (like: username, email, or other thing that you want)
         */
        $auth->initDBUserIdentifierColumnName('username');
        /**
         * Used to set a expiration time for the session cookie,
         * It's neccessary to use this method if you do not want your session cookie to be expired after your user close the browser.
         */
        $auth->initSessionCookieParams('day');

        /**
         * select which encryption method type we want to use
         */
        $auth->initencryptionMethod("BCRYPT");

        var_dump($auth->loginViaUserRawInfo(array(
            "username" => "test",
            "password" => "aBigSecurePassword"
            )));

        return;
    }

    /**
     * This method checks to see whether the requested username is exist or not.
     * @return void
     */
    public function chekingTheUser()
    {
        # The URL is look like this: http://127.0.0.1/RastgoFramework/authentication/chekingTheUser/username:hossein/
        $obj = new Authentication();
        $obj->initDbTableName('auth');
        $userInfo = self::$registry->request->getArgs();
        $username = explode(':', $userInfo[0]);

        if($obj->userIdentifierExists(array($username[0] => $username[1])))
        {
            echo 'Yes, The User Does Exist.';
            return;
        }

        echo 'Unfortunatly, The Requested User Does Not Exist!';
        return;
    }

    /**
     * This method shows us how to hash user password in action.
     *
     * The MD5 hash maker method will return used salt (a random salt if is not set), and also hashed of the password + salt.
     * These two strings will be needed for Authentication (verifying the user).
     */
    public function registerMD5()
    {
        #URL can be look like this: http://127.0.0.1/RastgoFramework/authentication/registerBcrypt/username:hossein/password:yourpasswordhere/email:yourmail@gmail.com/
        $obj = new Authentication();
        $obj->initDbTableName('auth');
        $userInfo = self::$registry->request->getArgs();
        $username = explode(':', $userInfo[0]);
        $password = explode(':', $userInfo[1]);
        $email = explode(':', $userInfo[2]);

//        echo $username[1];
//        echo '<br />';
//        echo $password[1];
//        echo '<br />';
//        echo $email[1];


        # Is used to create a hash and also a random salt (if the salt in not set) using this method, then we can insert them to the database using $obj->registerNewUser() method.
        echo '<br /><pre>';
        var_dump($obj->makePassHashMD5($password[1]));
        echo '</pre><br />';


        #And finally you can store the information in the database, using the registerNewUser() method (but it is optional).
    }

    /**
     * This method shows us how to verify user password if we had saved user information susing the md5 hash maker method.
     */
    public function verifyMD5Hash()
    {
        #URL can be look like this: http://127.0.0.1/RastgoFramework/authentication/registerBcrypt/username:hossein/password:yourpasswordhere/email:yourmail@gmail.com/
        $obj = new Authentication();
        $obj->initDbTableName('auth');
        $userInfo = self::$registry->request->getArgs();
        $username = explode(':', $userInfo[0]);
        $password = explode(':', $userInfo[1]);
        $email = explode(':', $userInfo[2]);

//        echo $username[1];
//        echo '<br />';
//        echo $password[1];
//        echo '<br />';
//        echo $email[1];


        $storedSaltInDatabase = '155ee3a3edd7a2fdf90ef7116658e71f';
        $storedHashOfPasswordInDatabase = '7c6e3b3bd9b5d7b46246cc02700d21da';


        echo '<br /><pre>';
        var_dump($obj->makePassHashMD5($password[1], $storedSaltInDatabase, $storedHashOfPasswordInDatabase));
        echo '</pre><br />';
    }

    /**
     * Through this method we are able to make a hash of user raw password with the Bcrypt password.
     */
    public function registerBcrypt()
    {
        #URL can be look like this: http://127.0.0.1/RastgoFramework/authentication/registerBcrypt/username:hossein/password:yourpasswordhere/email:yourmail@gmail.com/
        $obj = new Authentication();
        $obj->initDbTableName('auth');
        $userInfo = self::$registry->request->getArgs();
        $username = explode(':', $userInfo[0]);
        $password = explode(':', $userInfo[1]);
        $email = explode(':', $userInfo[2]);

//        echo $username[1];
//        echo '<br />';
//        echo $password[1];
//        echo '<br />';
//        echo $email[1];

        echo '<br /><pre>';
        var_dump($obj->makePassHashBcrypt($password[1]));
        echo '</pre><br />';

        #And finally you can store the information in the database, using the registerNewUser() method (but it is optional).
    }

    /**
     * This method shows us how to verify user password if we had saved user information susing the Bcrypt hash maker method.
     */
    public function verifyBcryptHash()
    {
        #URL can be look like this: http://127.0.0.1/RastgoFramework/authentication/registerBcrypt/username:hossein/password:yourpasswordhere/email:yourmail@gmail.com/
        $obj = new Authentication();
        $obj->initDbTableName('auth');
        $userInfo = self::$registry->request->getArgs();
        $username = explode(':', $userInfo[0]);
        $password = explode(':', $userInfo[1]);
        $email = explode(':', $userInfo[2]);

//        echo $username[1];
//        echo '<br />';
//        echo $password[1];
//        echo '<br />';
//        echo $email[1];

        #This info can be retrieved from the Database.
        $storedHashInDatabase = '$2a$12$mz7ZqbX4IlX3KznsflPxi.KaM/.IZRxDmNjg8Jd8vW.ydpnPsOfIm';

        echo '<br /><pre>';
        var_dump($obj->makePassHashBcrypt($password[1], $storedHashInDatabase));
        echo '</pre><br />';
    }
}
