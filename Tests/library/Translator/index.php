<?php
use root\library\Translator\index\Translator;

class TranslatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Translator Class Object
     * @var Translator
     */
    private $obj;

    public function setUp()
    {
        $this->obj = new Translator;
        $this->languagesFolderAddress = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . '__rfolder' . DIRECTORY_SEPARATOR . 'languages';
        //creating a folder named testLanguage, and a file named main.php in it
        $this->languageFolderDirectory = $this->languagesFolderAddress . DIRECTORY_SEPARATOR . 'testLanguage';
        $this->languageFileNameWithValidContent = $this->languagesFolderAddress . DIRECTORY_SEPARATOR . 'testLanguage' . DIRECTORY_SEPARATOR. 'main.php';
        $this->languageFileNameWithValidContent2 = $this->languagesFolderAddress . DIRECTORY_SEPARATOR . 'testLanguage' . DIRECTORY_SEPARATOR. 'secondFile.php';
        $this->languageFileNameWithInvalidContent = $this->languagesFolderAddress . DIRECTORY_SEPARATOR . 'testLanguage' . DIRECTORY_SEPARATOR. 'wrongContainingLanguageFile.php';
        if(file_exists($this->languageFileNameWithValidContent))
            unlink($this->languageFileNameWithValidContent);
        if(file_exists($this->languageFileNameWithValidContent2))
            unlink($this->languageFileNameWithValidContent2);
        if(file_exists($this->languageFileNameWithInvalidContent))
            unlink($this->languageFileNameWithInvalidContent);
        if(!file_exists($this->languageFolderDirectory))
            mkdir($this->languageFolderDirectory);

        file_put_contents($this->languageFileNameWithValidContent, '<?php return array("name" => "Hossein", "vendor" => "Rastgo");');
        file_put_contents($this->languageFileNameWithValidContent2, '<?php return array("profession" => "developer", "city" => "Tabriz");');
        file_put_contents($this->languageFileNameWithInvalidContent, '<?php return "some string here.";');
    }

    public function tearDown()
    {
        unlink($this->languageFileNameWithValidContent);
        unlink($this->languageFileNameWithValidContent2);
        unlink($this->languageFileNameWithInvalidContent);
        rmdir($this->languageFolderDirectory);
    }


    public function randomTypes()
    {
        return array(
            array(10),
            array(20.5),
            array(true),
            array(false),
            array(array()),
            array(array('some string')),
            array(new stdClass)
        );
    }

    /**
     * @test
     */
    public function setLanguageToPersian()
    {
        $language = 'Persian';
        $this->assertEquals($language, $this->obj->language($language));
    }

    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     * @dataProvider randomTypes
     */
    public function riseExceptionWhenLanguageIsSetByOtherTypesThanString($randomTypes)
    {
        $this->obj->language($randomTypes);
    }


    /**
     * @test
     */
    public function returnDefaultLanguage()
    {
        $expected = 'english';
        $actual = $this->obj->language();
        $this->assertEquals($expected, $actual);
    }


    /**
     * @test
     */
    public function changeLanguageFileExtension()
    {
        $expected = '.ini.php';
        $actual = $this->obj->languageFileExtention($expected);
        $this->assertEquals($expected, $actual);
    }


    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     * @dataProvider randomTypes
     */
    public function riseExceptionIfLanguageFileExtensionIsNotSetString($randomTypes)
    {
        $this->obj->languageFileExtention($randomTypes);
    }


    /**
     * @test
     */
    public function getDefaultLanguageFileExtension()
    {
        $expected = '.php';
        $actual = $this->obj->languageFileExtention();
        $this->assertEquals($expected, $actual);
    }


    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     */
    public function riseExceptionWhenLoadingLanguageFileWhichDoesNotExist()
    {
        $this->obj->loadLanguageFile('aLanguageFileWhichIsNotPresent');
    }


    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     */
    public function riseExceptionInfLanguageFileIsNotReturningArray()
    {
        $this->obj->language('testLanguage');   
        $this->obj->loadLanguageFile('wrongContainingLanguageFile');   
    }


    /**
     * @test
     */
    public function loadALanguageFileWithContentsAsArray()
    {
        $this->obj->languagesFolder($this->languagesFolderAddress);
        $this->obj->language('testLanguage');   
        $actual = $this->obj->loadLanguageFile('main');
        $this->assertInternalType('array', $actual);
    }


    /**
     * @test
     */
    public function loadALanguageFileUsing_loadLanguageFile_methodThenTranslateAKeyWord()
    {
        $this->obj->languagesFolder($this->languagesFolderAddress);
        $this->obj->language('testLanguage');   
        $result = $this->obj->loadLanguageFile('main');

        $actualForName = $this->obj->translate('name'); #hossein
        $actualForVendor = $this->obj->translate('vendor'); #Rastgo

        $this->assertEquals('Hossein', $actualForName);
        $this->assertEquals('Rastgo', $actualForVendor);
    }


    /**
     * @test
     */
    public function translateFromTwoDiferrentLanguageFiles_ByLoadingUsing_loadLanguageFile_And_SecondParameterOfTranslateMethod()
    {
        $this->obj->languagesFolder($this->languagesFolderAddress);
        $this->obj->language('testLanguage');   
        $this->obj->loadLanguageFile('main');

        $actualForName = $this->obj->translate('name'); #hossein
        $actualForVendor = $this->obj->translate('vendor'); #Rastgo

        $this->assertEquals('Hossein', $actualForName);
        $this->assertEquals('Rastgo', $actualForVendor);


        //// load a file using second parameter of translate method:
        $actualForProfession = $this->obj->translate('profession', 'secondFile');
        $this->assertEquals('developer', $actualForProfession);
    }


    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     */
    public function riseExceptionForUndefinedKeyWordInLanguageFile()
    {
        $this->obj->language('testLanguage');   
        $this->obj->translate('anUndefinedKeyWork', 'main');
    }

    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     */
    public function riseExceptionIfTranslateFirstParameterValueIsNotTypeOfStringOrInteger()
    {
        $this->obj->language('testLanguage');   
        $this->obj->translate(new stdClass, 'main');
        $this->obj->translate(false, 'main');
        $this->obj->translate(array(), 'main');
    }

    /**
     * @test
     */
    public function ReturnsNullWhenLanguagesFolderAddressIsNotSpecified()
    {
        $this->assertNull($this->obj->languagesFolder());
    }

    /**
     * @test
     */
    public function setLanguagesFolderAddress()
    {
        $this->obj->languagesFolder($this->languagesFolderAddress);

        // Get set languages folder address (when first parameter of languagesFolderAddress is not specified or is NULL)
        $this->assertEquals($this->languagesFolderAddress, $this->obj->languagesFolder());

    }

    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     * @dataProvider randomTypes
     */
    public function riseExceptionWhenLanguagesFolderMethodFirstParameterTypeIsNotOfStringOrNull($randomTypes)
    {
        $this->obj->languagesFolder($randomTypes);
    }



    /**
     * @test
     * @expectedException \root\library\Translator\exception\TranslatorException
     * @expectedExceptionMessage Languages Folder Address is not specified and is NULL!
     */
    public function riseAnExceptionWhenLoadingLanguageFileWhileLanguagesFolderAddressIsNull()
    {
        // Because the method we are dealing with (loadLanguageFile) has two different exception thrown in it then we have to check its message rather than just checking to see if any exception is thrown.
        $this->obj->language('testLanguage');
        $this->obj->loadLanguageFile('main');
    }
}