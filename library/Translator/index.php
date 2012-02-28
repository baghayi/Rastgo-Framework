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
        
        if($fileName !== null){
             $this->loadLanguageFile($fileName);
        }
        
        return isset($this->languageArray[$keyWord])?$this->languageArray[$keyWord]:null;
    }
    
    public function loadLanguageFile($fileName){
        global $registry;
        $fileAddress = FILE_PATH . 'application' . DIRECTORY_SEPARATOR . '__rfolder' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $this->defaultLanguage . DIRECTORY_SEPARATOR . $fileName . $this->langFileExtension;
        
        if(file_exists($fileAddress)){
            $this->languageArray = require_once $fileAddress;
            
            if(!is_array($this->languageArray)){
                $registry->error->reportError('The Returned Value From The Called Language File Is Not An Array!', __LINE__, __METHOD__, true);
                return;
            }
            
            return 1;
        }
        
        else{
            $registry->error->reportError('Wanted Language Package (File) Does Not Exists!', __LINE__, __METHOD__, true);
            return;
        }
    }
}