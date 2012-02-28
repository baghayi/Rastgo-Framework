<?php
namespace root\library\Template\index;
class Template {
    /**
     * gives us the direction of template files where they are located.
     * @var string $baseDir
     */
    private $baseDir;
    /**
     * The extension of template files that we are using.
     * @var string $defaultTemplateExtension
     */
    private $defaultTemplateExtension = '.php';

    /**
     * Making ready the template directory address, and setting it in the $baseDir property.
     */
    public function __construct() {
        $this->baseDir = FILE_PATH . 'application' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . TEMPLATE_FOLDER_NAME;
    }

    /**
     *
     * @param string $dir to set or change the location of template files folder where template files are located.
     */
    public function setBaseDir($dir) {
        $this->baseDir = $dir;
        return 1;
    }

    /**
     *
     * @return string gives us the template files folder where they are located.
     */
    public function getBaseDir() {
        return $this->baseDir;
    }

    /**
     *
     * @param string $ext To set or change the extention of template files.
     */
    public function setExtension($ext) {
        $this->defaultTemplateExtension = $ext;
        return 1;
    }

    /**
     *
     * @return string Gives us the extention of template files.
     */
    public function getExtension() {
        return $this->defaultTemplateExtension;
    }

    /**
     *  One of the main methods to work with template files to render the template files, But just render and return the results (Not showing them with such as echo() method) | ( Reverse of renderTemplate() Method ).
     * @param string $template The name of the template file which we want to render it (without its extension | just file's name).
     * @param array $vars The name and value of variables in template file to replace them with our values.
     * @param string $baseDir The folder of template files where they are located.
     * @return string returns the rendered template file .
     */
    public function loadTemplate($template, $vars = array(), $baseDir=NULL) {
        global $registry;
        
        if ($baseDir == NULL){
            $baseDir = $this->getBaseDir();
        }

        $templatePath = $baseDir . DIRECTORY_SEPARATOR . $template . $this->getExtension();
        
        if (!file_exists($templatePath)) {
            $registry->error->reportError('Could not include template ' . $templatePath, __LINE__, __METHOD__, true);
            return;
        }
        
        return $this->loadTemplateFile($templatePath, $vars);
    }

    /**
     *  One of the main methods to work with template files to render the template files, And showing the results at the end instead of returning the results | ( Reverse of loadTemplate() Method ).
     * @param string $template The name of the template file which we want to render it (without its extension | just file's name).
     * @param array $vars The name and value of variables in template file to replace them with our values.
     * @param string $baseDir The folder of template files where they are located.
     */
    public function renderTemplate($template, $vars = array(), $baseDir=NULL) {
        echo $this->loadTemplate($template, $vars, $baseDir);
        return;
    }

    /**
     *  This the main core of our class that makes ready template files for us and replacing all variables with our values.
     * @param string $templatePath The exact path of template file (Including template path folder, template file name and its extension).
     * @param array $vars The name and value of variables in template file to replace them with our values.
     * @return string Returns the rendered template files as result .
     */
    private function loadTemplateFile($templatePath, $vars) {
        extract($vars, EXTR_OVERWRITE);
        ob_start();
        require $templatePath;
        return ob_get_clean();
    }

}
