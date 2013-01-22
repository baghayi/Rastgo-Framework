<?php
use root\library\FormHandler\index\FormHandler;

class FormTest extends PHPUnit_Framework_TestCase
{
    public $className = "FormHandler";
    public static $classObj;

    public function setUp()
    {
        self::$classObj = new FormHandler();
    }

    /**
     * @test
     */
    public function emailConstantExists()
    {
        $reflectionClassObj = new ReflectionClass('root\library\FormHandler\index\FormHandler');
        $constantsList = $reflectionClassObj->getConstants();
        $constantsListKeys = array_keys($constantsList);

        $this->assertContains('Email', $constantsListKeys, "Unfortunately Email Constant Does not exist in the {$this->className}.");
    }

    /**
     * @test
     */
    public function inputConstantExists()
    {
        $reflectionClassObj = new ReflectionClass('root\library\FormHandler\index\FormHandler');
        $constantsList = $reflectionClassObj->getConstants();
        $constantsListKeys = array_keys($constantsList);

        $this->assertContains('Input', $constantsListKeys, "Unfortunately Input Constant Does not exist in the {$this->className}.");
    }


    /**
     * @test
     */
    public function loadEmailClassUsingLoadMethodOfFormClassAndGetItsObject()
    {
        $emailClassObj = self::$classObj->load(FormHandler::Email);
        $this->assertInstanceOf('\root\library\FormHandler\Email\Email', $emailClassObj, 'It is not an Email class Object!');
    }

    /**
     * @test
     */
    public function loadInputClassUsingLoadMethodOfFormClassAndGetItsObject()
    {
        $inputClassObj = self::$classObj->load(FormHandler::Input);
        $this->assertInstanceOf('\root\library\FormHandler\Input\Input', $inputClassObj, 'It is not an Input class Object!');
    }

    /**
     * @test
     * @expectedException \root\library\FormHandler\exception\FormHandlerException
     */
    public function loadAClassUsingLoadMethodInWhichProvidedClassInfoIsNotAValidString()
    {
        self::$classObj->load('AClassNameWhichDoesNotExist');
    }

    /**
     * @test
     * @expectedException \root\library\FormHandler\exception\FormHandlerException
     */
    public function loadAClassUsingLoadMethodInWhichRequestedClassFileInNotExist()
    {
        self::$classObj->load('\root\library\FormHandler\SomethingDoesNotExist\SomethingDoesNotExist::SomethingDoesNotExist');
    }

}