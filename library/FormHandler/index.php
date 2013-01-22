<?php
namespace root\library\FormHandler\index;
use \root\library\FormHandler\exception\FormHandlerException;

class FormHandler
{
    /**
     * Email constant contain information if Email handler class so that this class can extract and use them to load them properly.
     */
    const Email = '\root\library\FormHandler\Email\Email::Email';
    /**
     * Input constant contain information if Input handler class so that this class can extract and use them to load them properly.
     */
    const Input = '\root\library\FormHandler\Input\Input::Input';

    /**
     * Loads requested class.
     * @param  mixed $className Name of a class of one of class's constant to call a specific class.
     * @return boolean | object            In success object of requested class will returned otherwise false will return.
     */
    public function load($requestedClass)
    {
        $classInfo = $this->parseRequestedClassConstant($requestedClass);

        $fileAddress = __DIR__ . DIRECTORY_SEPARATOR .$classInfo['file'] . '.php';

        if(!is_readable($fileAddress) or !file_exists($fileAddress))
            throw new FormHandlerException('Requested class file does not exist.');

        return new $classInfo['namespace'];
    }


    /**
     * This class is to extract requested class's constants and return those info as an array to use them to load requested class.
     * @param  string $constant Strings of which contain requested classes info.
     * @return array | boolean           False if process was not succeed, otherwise extracted string as in array containing elements which their key to be 'namespace' and 'file'.
     */
    private function parseRequestedClassConstant($constant)
    {
        $extractedConstant = explode('::', $constant, 2);

        $totalAmountOfExtractedConstantElements = count($extractedConstant);
        if($totalAmountOfExtractedConstantElements !== 2)
            throw new FormHandlerException('Provided class Info is not a valid string.');

        return array(
            'namespace' => $extractedConstant[0],
            'file'      => $extractedConstant[1]
        );
    }
}