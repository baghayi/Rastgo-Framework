<?php
namespace root\library\FormHandler\exception;

class FormHandlerException extends \Exception
{
    public function __construct($message)
    {
        $errorMessageTemplate = "%s <em>(Occurred on line %d, in %s)</em>";
        $this->message = sprintf($errorMessageTemplate, $message, $this->line, $this->file);
        return;
    }
}