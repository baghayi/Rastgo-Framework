<?php
use \root\library\FormHandler\Input\Input;

class InputTest extends PHPUnit_Framework_TestCase
{
    public static $classObj;

    public function setUp()
    {
        self::$classObj = new Input();
    }

    /**
     * @test
     */
    public function checkIfTypeStoringPropertyExist()
    {
        $this->assertClassHasAttribute('type', '\root\library\FormHandler\Input\Input', 'Unfortunately type property does not exist in class \'Input\'!');        
    }

    /**
     * @test
     */
    public function getTypeDefaultValueWhichShouldBePOSTByDefault()
    {
        $typeDefaultValue = self::$classObj->type();
        $this->assertEquals(Input::TYPE_POST, $typeDefaultValue, 'Type default value is not set or is not POST!');
    }


    /**
     * @test
     */
    public function changeTypeValueToGET()
    {
        $newType = Input::TYPE_GET;
        self::$classObj->type($newType);
        $typeNewValue = self::$classObj->type();
        $this->assertEquals($newType, $typeNewValue, 'Result was not as expected which should be inserted value that was GET!');
    }

    /**
     * @test
     * @expectedException \root\library\FormHandler\exception\FormHandlerException
     */
    public function setTypeValueToSomethingThatIsNotSupportedYet()
    {
        $newType = 'somethingthatdoesnotexist';
        self::$classObj->type($newType);
    }


    /**
     * @test
     */
    public function doesItMakeProblemToProvideTypesInLowerCase()
    {
        $newType = 'get';
        $returnedType = self::$classObj->type($newType);
        $this->assertEquals(INPUT::TYPE_GET, $returnedType);
    }

    /**
     * @test
     */
    public function get_name_KeyOfPostTypeInputValue()
    {
        $_POST['name'] = 'Rastgo';
        $_POST['attribute'] = 'Framework';

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValue('name');
        $this->assertEquals($_POST['name'], $returnedValue);
    }

    /**
     * @test
     */
    public function get_attribute_KeyOfPostTypeInputValue()
    {
        $_POST['name'] = 'Rastgo';
        $_POST['attribute'] = 'Framework';

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValue('attribute');
        $this->assertEquals($_POST['attribute'], $returnedValue);
    }

    /**
     * @test
     */
    public function getAndUndefinedIndexKeyOfPostTypeInputValue()
    {
        $_POST['name'] = 'Rastgo';
        $_POST['attribute'] = 'Framework';

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValue('anUndefinedIndexKey');
        $this->assertEquals(NULL, $returnedValue);
    }

    /**
     * @test
     * @expectedException \root\library\FormHandler\exception\FormHandlerException
     */
    public function getValue_MethodCanOnlyAcceptStringOrInteger()
    {
        $_POST['name'] = 'Rastgo';
        $_POST['attribute'] = 'Framework';

        self::$classObj->type(Input::TYPE_POST);
        self::$classObj->getValue(array());
    }

    /**
     * @test
     */
    public function willItReturnResultIfPostKeyIndexIsAnInteger()
    {
        $_POST[1] = 'One';

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValue(1);
        $this->assertEquals($_POST[1], $returnedValue);
    }

    /**
     * @test
     */
    public function get_lastName_indexOfGetTypeVariable()
    {
        $_GET['lastName'] = "Baghayi";
        self::$classObj->type(Input::TYPE_GET);
        $returnedValue = self::$classObj->getValue('lastName');
        $this->assertEquals($_GET['lastName'], $returnedValue);
    }

    /**
     * @test
     */
    public function gettingListOfPostInputTypeValues()
    {
        $_POST['name'] = "Rastgo";
        $_POST['type'] = "Framework";
        $_POST['author'] = "Baghayi";

        $expectedValue = array(
            'name' => 'Rastgo',
            'author' => 'Baghayi'
        );

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValues(array(
            'name',
            'author'
        ));
        $this->assertSame($expectedValue, $returnedValue);
    }


    /**
     * @test
     */
    public function gettingListOfPostInputTypeValuesWhichSomeIsNotProvided()
    {
        $_POST['name'] = "Rastgo";
        // $_POST['type'] = "Framework";
        // $_POST['author'] = "Baghayi";

        $expectedValue = array(
            'name' => 'Rastgo',
            'author' => NULL
        );

        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValues(array(
            'name',
            'author'
        ));
        $this->assertSame($expectedValue, $returnedValue);
    }


    /**
     * @test
     */
    public function getListOfGetInputTypeValues()
    {
        $_GET['name'] = "Rastgo";
        $_GET['type'] = "Framework";
        $_GET['author'] = "Baghayi";

        $expectedValue = array(
            'name' => 'Rastgo',
            'author' => 'Baghayi'
        );

        self::$classObj->type(Input::TYPE_GET);
        $returnedValue = self::$classObj->getValues(array(
            'name',
            'author'
        ));
        $this->assertSame($expectedValue, $returnedValue);
    }

    /**
     * @test
     */
    public function returnAllAvailabeInputValuesIfProvidedArgumentForGetTypeIsEmptyArray()
    {
        $_GET['name'] = "Rastgo";
        $_GET['type'] = "Framework";
        $_GET['author'] = "Baghayi";

        $expectedValue = $_GET;
        
        self::$classObj->type(Input::TYPE_GET);
        $returnedValue = self::$classObj->getValues(array());
        $this->assertSame($expectedValue, $returnedValue);
    }


    /**
     * @test
     */
    public function returnAllAvailabeInputValuesIfProvidedArgumentForPostTypeIsEmptyArray()
    {
        $_POST['name'] = "Rastgo";
        $_POST['type'] = "Framework";
        $_POST['author'] = "Baghayi";

        $expectedValue = $_POST;
        
        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValues(array());
        $this->assertSame($expectedValue, $returnedValue);
    }


    /**
     * @test
     */
    public function getAllAvailableValuesOfTypePostWhen_getValues_FirstArgumentIsNotProvided()
    {
        $_POST['name'] = "Rastgo";
        $_POST['type'] = "Framework";
        $_POST['author'] = "Baghayi";

        $expectedValue = $_POST;
        
        self::$classObj->type(Input::TYPE_POST);
        $returnedValue = self::$classObj->getValues();
        $this->assertSame($expectedValue, $returnedValue);
    }
}