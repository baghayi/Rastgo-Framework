<?php
class template {

    /**
     *  gives us the direction of template files where they are located
     * @var string $baseDir
     */
    private static $baseDir = '.';
    /**
     *  The extension of template files that we are using
     * @var string $defaultTemplateExtension
     */
    private static $defaultTemplateExtension = '.php';

    /**
     *
     * @param string $dir to set or change the location of template files folder where template files are located
     */
    public static function setBaseDir($dir) {
        self::$baseDir = $dir;
    }

    /**
     *
     * @return string gives us the template files folder where they are located 
     */
    public static function getBaseDir() {
        return self::$baseDir;
    }

    /**
     *
     * @param string $ext To set or change the extention of template files
     */
    public static function setExtension($ext) {
        self::$defaultTemplateExtension = $ext;
    }

    /**
     *
     * @return string Gives us the extention of template files
     */
    public static function getExtension() {
        return self::$defaultTemplateExtension;
    }

    /**
     *  One of the main methods to work with template files to render the template files, But just render and return the results (Not showing them with such as echo() method) | ( Reverse of renderTemplate() Method )
     * @param string $template The name of the template file which we want to render it (without its extension | just file's name)
     * @param array $vars The name and value of variables in template file to replace them with our values
     * @param string $baseDir The folder of template files where they are located
     * @return string returns the rendered template file 
     */
    public static function loadTemplate($template, $vars = array(), $baseDir=null) {
        if ($baseDir == null) {
            $baseDir = self::getBaseDir();
        }

        $templatePath = $baseDir . '/' . $template . '' . self::getExtension();
        if (!file_exists($templatePath)) {
            throw new Exception('Could not include template ' . $templatePath);
        }

        return self::loadTemplateFile($templatePath, $vars);
    }

    /**
     *  One of the main methods to work with template files to render the template files, And showing the results at the end instead of returning the results | ( Reverse of loadTemplate() Method )
     * @param string $template The name of the template file which we want to render it (without its extension | just file's name)
     * @param array $vars The name and value of variables in template file to replace them with our values
     * @param string $baseDir The folder of template files where they are located
     */
    public static function renderTemplate($template, $vars = array(), $baseDir=null) {
        echo self::loadTemplate($template, $vars, $baseDir);
    }

    /**
     *  This the main core of our class that makes ready template files for us and replacing all variables with our values
     * @param string $templatePath The exact path of template file (Including template path folder, template file name and its extension)
     * @param array $vars The name and value of variables in template file to replace them with our values
     * @return string Returns the rendered template files as result 
     */
    private static function loadTemplateFile($templatePath, $vars) {
        extract($vars, EXTR_OVERWRITE);
        $template_return = '';
        ob_start();
        require $templatePath;
        $template_return = ob_get_clean();
        return $template_return;
    }

}
?>