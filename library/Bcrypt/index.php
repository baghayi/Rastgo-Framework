<?php
namespace root\library\Bcrypt\index;

class Bcrypt {

    private $rounds, $prefix;

    /**
     * Our construct method to set and also config some settings, as described below (for parameters of the construct method).
     * @global object $registry Globalizing the object of the Registry class.
     * @param string|int $prefix To use it in making the salt in the uniqid() function in one of the class methods.
     * @param int $rounds By increasing this number, you will cause the class to make the hash slower that default. And vice versa.
     * @return void
     */
    public function __construct($prefix = '', $rounds = 12) 
    {
        global $registry;
        
        if(!is_numeric($rounds))
        {
            $registry->error->reportError("The second parameter of the __construct method must be an integer!", __LINE__, __METHOD__, true, 'authentication');
        }
        
        if (CRYPT_BLOWFISH != 1) 
        {
            
            $registry->error->reportError("bcrypt is not supported in this installation. See http://php.net/crypt", __LINE__, __METHOD__, true, 'authentication');
        }

        $this->rounds = $rounds;
        $this->prefix = $prefix;

        return;
    }

    /**
     * Using this method we can get a hash of our password.
     * @param string $input Users' password to be hashed.
     * @return mixed false on failure, the hash on success.
     */
    public function hash($input) 
    {
        $hash = crypt($input, $this->getSalt());

        if (strlen($hash) > 13) {
            return $hash;
        }

        return false;
    }

    /**
     * Using this method we can verify to know whether the user's inserted raw password is equal with the hash that we have got in the database or nor.
     * @param string $input Users' raw password to be compared with the hash in database or anywhere it is.
     * @param string $existingHash The hash that is stored in database or anywhere that is stored to be checked with the raw User password.
     * @return boolean True on success, or False on failure.
     */
    public function verify($input, $existingHash) 
    {
        $hash = crypt($input, $existingHash);

        return $hash === $existingHash;
    }

    /**
     * This method makes (in fact) a final salt to use it for encrypting our password  [ as Blowfish encryption / To tell the crypt() function to use Blowfish method.].
     * @return string It will return a string to be used in hashing the password as a salt.
     */
    private function getSalt() 
    {
        /**
         * the base64 function uses +'s and ending ='s; translate the first, and cut out the latter
         */
        return sprintf('$2a$%02d$%s', $this->rounds, substr(strtr(base64_encode($this->getBytes()), '+', '.'), 0, 22));
    }

    /**
     * Via this method Class will make a binary string that can use it in the getSalt() method to use it in making the salt for crypt() function.
     * @return mixed Will return a binary string!
     */
    private function getBytes() 
    {
        $bytes = '';

        /**
         * To check whether openssl_random_pseudo_bytes exists and also the operation-system is not windows, becaues Open ssl is slow on windows!
         */
        if (function_exists('openssl_random_pseudo_bytes') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) 
        {
            $bytes = openssl_random_pseudo_bytes(18);
        }

        if ($bytes === '' && is_readable('/dev/urandom') && ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) 
        {
            $bytes = fread($hRand, 18);
            fclose($hRand);
        }

        if ($bytes === '') 
        {
            $key = uniqid($this->prefix, true);

            /**
             * 12 rounds of HMAC must be reproduced / created verbatim, no known shortcuts.
             * Salsa20 returns more than enough bytes.
             */
            for ($i = 0; $i < 12; $i++) 
            {
                $bytes = hash_hmac('salsa20', microtime() . $bytes, $key, true);
                usleep(10);
            }
        }

        return $bytes;
    }
}