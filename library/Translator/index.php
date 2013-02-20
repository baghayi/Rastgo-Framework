<?php
namespace root\library\Translator\index;

use \root\library\Translator\exception\TranslatorException;

final class Translator
{
    /**
     * A language package / folder name that language files is located in it.
     * @var string
     */
    private $languageFolder = 'english';

    /**
     * The extension of language file.
     * @var string
     */
    private $langFileExtension = '.php';

    /**
     * Returned array from requested language file will store in this property to be used by other methods.
     * @var array
     */
    private $languageArray = array();


    /**
     * Stores directory address of languages which will be stored in this folder.
     * @var mixed
     */
    private $languagesFolderAddress = NULL;
    

    /**
     * Use language method instead.
     * For description of this method check language method of this class.
     * @deprecated
     * @param mixed $language Null will return current selected language, string will set the language.
     */
    public function setDefaultLang($language = NULL)
    {
        return $this->language($language);
    }


    /**
     * Using this method we can either define which language package / folder to read in, or to get back which package / folder is already chosen.
     * By specifying first parameter we can define language package / folder.
     * To know which language package / folder is already been chosen the first parameter should not be passed any value of NULL should be passed.
     *
     * If type of first parameter is other than NULL or string then an TranslatorException will thrown.
     * 
     * @param  mixed $language Null for getting current language package, string for specifying a language package.
     * @return string           Current / new specified language package will return.
     */
    public function language($language = NULL)
    {
        if(! is_string($language) && $language !== NULL)
            throw new TranslatorException('Language names can only be string type!');

        if(is_null($language))
            return $this->languageFolder;

        $this->languageFolder = $language;
        return $this->languageFolder;
    }

    /**
     * Use languageFileExtention method instead.
     * For description of this method check languageFileExtention method of this class.
     * @deprecated
     * @param mixed $extension Null will return current selected language file extension, string will set the language file extension.
     */
    public function setLangFileExtension($extension = NULL)
    {
        return $this->languageFileExtention($extension);
    }
    

    /**
     * This method lets us to whether specify a language file extension or retrieve current set value.
     * If first parameter is not specified or NULL is given then the current extension set value will return.
     *     Otherwise provided value for first parameter will be set as language files extension.
     *     In addition that provided value for first parameter other than NULL or string type will cause an TranslatorException to be thrown.
     *     Also that for file addition you need to prefix a dot ( . ) before extension name, e.g ( .php, ... ).
     * @param  mixed $extension NULL will only cause a value to be returned and not set, but provided value of type string will replace the current language file extension.
     * @return string            Current language file extension.
     */
    public function languageFileExtention($extension = NULL)
    {
        if(! is_string($extension) && $extension !== NULL)
            throw new TranslatorException('Language file extension can only be string type!');

        if(is_null($extension))
            return $this->langFileExtension;

        $this->langFileExtension = $extension;
        return $this->langFileExtension;
    }
    

    /**
     * Using this method we can translate a requested key word / index to a value which is specified for that key word / index.
     *     For instance, when an array is defined in language file, the key word / index will be the index or key of an element in array and its value an translated value will return.
     *     array('vendor' => 'Rastgo'), in this example the `vendor` is a key word or index and by passing it to first parameter of this method its translated value which is `Rastgo` will return.
     * First parameter's value type has to be one of string or integer otherwise a TranslatorException will be thrown.
     * Of if requested index / key word is not found in the array which is defined in language will then a TranslatorException will be thrown.
     * The second parameter can be used for specifying a language file to retrieve array from that file, which you can use loadLanguageFile instead of specifying second parameter.
     * @param  mixed $index    Value type can be of string or integer as key word or index, and its corresponding element's value in an array will return.
     * @param  mixed $fileName NULL will not cause an language file to be selected, otherwise if an string is provided then requested language file corresponding to that string fill be used to select the first parameter element's value within it.
     * @return mixed           The value of the corresponding index / key word in the language file's array will return.
     */
    public function translate($index, $fileName = NULL)
    {
        if( ! is_string($index) && ! is_integer($index))
            throw new TranslatorException("First parameter of method translate has to be a type of string or integer.");

        if( ! is_null($fileName) )
            $this->languageArray = $this->loadLanguageFile($fileName);
        
        if( ! isset($this->languageArray[$index]) )
            throw new TranslatorException("Requested key word / index ({$index}) in the language file is not defined!");

        return $this->languageArray[$index];
    }
    

    /**
     * Using this method we can specify which language file in a language package to be read from.
     * The value of first parameter can be a file name which is located inside a language package, and that its extension MUST not be provided, and only the file name should be provided.
     * There are two conditions that if neither of them are met then TranslatorException will be thrown.
     *     1. If language fill does not exists or is not readable.
     *     2. If returned value from language file is not an array.
     * If everything is alright then returned array from language file will store in $languageArray property of this class to be used by other methods and also return array from language will return from this method too.
     * 
     * @param  mixed $fileName Language file name.
     * @return array           An array which is returned by requested language file.
     */
    public function loadLanguageFile($fileName)
    {
        if( is_null($this->languagesFolderAddress) )
            throw new TranslatorException('Languages Folder Address is not specified and is NULL!');

        $fileAddress = $this->languagesFolderAddress . DIRECTORY_SEPARATOR . $this->languageFolder . DIRECTORY_SEPARATOR . $fileName . $this->langFileExtension;
        
        if(!file_exists($fileAddress) || !is_readable($fileAddress))
            throw new TranslatorException('Requested languages file does not exists or is not readable!');


        $this->languageArray = require $fileAddress;

        if(!is_array($this->languageArray))
            throw new TranslatorException('Returned value from called language file is not array!');            

        return $this->languageArray;
    }


    /**
     * By this method we can specify languages root folder address which language packages / folders will be stored at.
     * If first parameter is not specified or NULL if provided then current value of the languagesFolderAddress will return otherwise if it is string type then new languagesFolderAddress will set.
     * @param  mixed $languagesFolderAddress Languages folder address as string or for getting current value of languagesFolderAddress null should be provided.
     * @return mixed                         Current or newly set value of the languagesFolderAddress will return.
     */
    public function languagesFolder($languagesFolderAddress = NULL)
    {
        if( !is_null($languagesFolderAddress) && 
            !is_string($languagesFolderAddress) )
            throw new TranslatorException('First Parameter value type has to be either String or NULL.');

        if(! is_null($languagesFolderAddress) )
            $this->languagesFolderAddress = $languagesFolderAddress;

        return $this->languagesFolderAddress;
    }

}