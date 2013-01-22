<?php
namespace root\library\FormHandler\Email;

class Email 
{

    /**
     * Through this function we check to see whether an email address is valid or not.
     * @param  string  $anEmailAddress An Email address to check its validity.
     * @return boolean                 True if email address is valid, otherwise false will return.
     */
    public function isValid($anEmailAddress)
    {
        $result = filter_var($anEmailAddress, FILTER_VALIDATE_EMAIL);
        if($result === False)
            return False;
        else
            return True;
    }
}