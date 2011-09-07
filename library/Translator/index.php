<?php
namespace root\library\Translator\index;

final class Translator {
    
    private $defaultLanguage ='english', $langFileExtension = '.php', $languageArray = array();
    
    public function setDefaultLang($language){
        $this->defaultLanguage = $language;
        return 1;
    }
    
    public function setLangFileExtension($extension){
        $this->langFileExtension = $extension;
        return 1;
    }
    
    public function translate($keyWord, $fileName = null){
        if($fileName !== null)
             $this->loadLanguageFile($fileName);
        
        $result = isset($this->languageArray[$keyWord])?$this->languageArray[$keyWord]:null;
        return $result;
    }
    
    public function loadLanguageFile($fileName){
        global $registry;
        $fileAddress = FILE_PATH . 'languages' . DS . $this->defaultLanguage . DS . $fileName . $this->langFileExtension;
        if(file_exists($fileAddress)){
            include $fileAddress;
            if(!isset($language) || !is_array($language)){
                $registry->error->reportError('The Variable inside of the language file is not setted as ( $language ) or It is not an array', __LINE__, __METHOD__, true);
                return;
            }
            $this->languageArray = $language;
            return 1;
        }else{
            $registry->error->reportError('Wanted Language Package Does Not Exists!', __LINE__, __METHOD__, true);
            return;
        }
    }
}