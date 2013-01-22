<?php
use root\core\baseController\baseController,
    root\library\FormHandler\index\FormHandler,
    root\library\FormHandler\Input\Input;

class FormHandlerController extends baseController {

    public function validateAnEmail()
    {
        $emailAddress = 'hossein@gmail.com'; # :)
        $form = self::$registry->lib->call('FormHandler');
        $email = $form->load(FormHandler::Email);
        $result = $email->isValid($emailAddress);

        if($result === true)
            echo ':)';
        else
            echo ':(';
    }


    public function getAPostValue()
    {
        $_POST['name'] = 'Hossein';
        $form = self::$registry->lib->call('FormHandler');
        $input = $form->load(FormHandler::Input);
        $input->type(Input::TYPE_POST); // is optional for only POST, for GET it must be set.
        echo $input->getValue('name'); // "Hossein"
    }

    public function getAGetValue()
    {
        $_GET['lastName'] = 'Baghayi';
        $form = self::$registry->lib->call('FormHandler');
        $input = $form->load(FormHandler::Input);
        $input->type(Input::TYPE_GET); // is optional for only POST, for GET it must be set.
        echo $input->getValue('lastName'); // "Baghayi"
    }

    /**
     * For get you have to only set it using type method of Input class.
     *     $input->type(Input::TYPE_GET);
     */
    public function getMultiplePostsValue()
    {
        $_POST['name'] = 'Hossein';
        $_POST['profession'] = 'Web Developer';
        $_POST['age'] = '???';

        $form = self::$registry->lib->call('FormHandler');
        $input = $form->load(FormHandler::Input);
        $values = $input->getValues(array(
            'name',
            'profession'
        )); // array('name' => 'Hossein', 'profession' => 'Web Developer');

        var_dump("<pre>", $values,"</pre>");
        exit;
    }
    

    public function index(){}
}