<?php
use root\library\FormHandler\Email\Email;

class EmailTest extends PHPUnit_Framework_TestCase
{
    public static $classObj;

    public function setUp()
    {
        self::$classObj = new Email;
    }

    /**
     * @test
     */
    public function checkAValidEmailAddress()
    {
        $validEmailAddress = 'hossein@adomain.com';
        $result = self::$classObj->isValid($validEmailAddress);
        $this->assertTrue($result, 'A valid email couldn\'t pass the validation test!');
    }

    /**
     * @test
     */
    public function checkAnInvalidEmailAddress()
    {
        $invalidEmailAddress = 'thiisnota validemail addre s';        
        $result = self::$classObj->isValid($invalidEmailAddress);
        $this->assertFalse($result, 'An invalid email Must Not pass the validation test!');
    }
}