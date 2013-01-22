<?php
namespace root\library\FormHandler\Input;

use \root\library\FormHandler\exception\FormHandlerException;

class Input 
{
    /**
     * This constant is to use it when specifying type as POST using `type` method.
     */
    const TYPE_POST = 'POST';

    /**
     * This constant is to use it when specifying type as GET using `type` method.
     */
    const TYPE_GET = 'GET';

    /**
     * Stores the type of Input fields which currently supports only GET and POST (it is case-insensitive).
     * @var string
     */
    private $type = self::TYPE_POST;

    /**
     * Currently available types which this class supports are being listed in this array.
     * So that we can check against it to see whether user has provided us the proper type or not.
     * @var array
     */
    private $availableTypes = array(self::TYPE_POST, self::TYPE_GET);


    /**
     * Will set input field type which currently this class supports POST and GET.
     * Using this class we can both get the current type and or set a new type.
     * If first parameter is set then is will replace a new provided type with current one.
     * This method will return the current or new set type if or not first parameter is set.
     * @param  string $newType One of POST of GET must be the inserted value and defined input field type.
     * @return string          Will return the current or newly set input field type.
     */
    public function type($newType = NULL)
    {
        if(is_string($newType))
            $newType = strtoupper($newType);

        if($newType !== NULL && !in_array($newType, $this->availableTypes))
            throw new FormHandlerException('Provided type in first argument is not supported!');

        if($newType !== null)
            $this->type = $newType;

        return $this->type;
    }


    /**
     * Using this method we can get the value of _GET or _POST variables and if there are not provided via form fields
     *     or query string then NULL will return.
     * If first argument type is not one of string or integer then an exception will rise.
     * @param  string | integer $arrayIndex Key name or number of _POST or _GET value to get its value.
     * @return mixed             If requested key found then its value will return otherwise NULL will return.
     */
    public function getValue($arrayIndex)
    {
        if(false === is_string($arrayIndex) && // is provided first argument's value is not string
           false === is_integer($arrayIndex)) // or is not integer, then return NUll.
            throw new FormHandlerException('First argument type has to be either String or Integer.');

        switch ($this->type) {
            case self::TYPE_POST:
                return (isset($_POST[$arrayIndex])) ? $_POST[$arrayIndex] : NULL;
                break;
            case self::TYPE_GET:
                return (isset($_GET[$arrayIndex])) ? $_GET[$arrayIndex] : NULL;
                break;
        }
    }


    /**
     * Using this method it is possible to get multiple values of _POST or _GET variables.
     *     This method functions in a similar way to `getValue` except that this one returns multiple values.
     *     If first parameter is not defined then all available values of specified input type will return.
     * @param  array  $arrayIndexList List of requested inputs name to get their values.
     * @return array                 Returned array elements key will be requested input name and its value will be inputs value that user provided in forms and if it is not found then null will be set to that key rather than omitting that key from final array elements list.
     */
    public function getValues(array $arrayIndexList = array())
    {
        if(empty($arrayIndexList))
            return $this->getAllInputValuesOfSelectedType();

        $result = array();
        array_map(function($eachRequestedElement) use(&$result){
            $result[$eachRequestedElement] = $this->getRequestedValuesByType($eachRequestedElement);
        }, $arrayIndexList);

        return $result;
    }

    /**
     * This method first checks to see check input type is selected, then will check to see if requested type (i.e _POST or _GET) has requested key that is provided as first argument of method.
     *     And if it exists then its value will return otherwise NULL will return.
     * @param  mixed $eachRequestedElement string or integer to check it against _POST or _GET array variable keys.
     * @return mixed                       Will return the value of _POST or _GET requested key's value if found, otherwise null will return.
     */
    private function getRequestedValuesByType($eachRequestedElement)
    {
        switch($this->type){
            case self::TYPE_POST:
                return (isset($_POST[$eachRequestedElement])) ? $_POST[$eachRequestedElement] : NULL;
                break;
            case self::TYPE_GET:
                return (isset($_GET[$eachRequestedElement])) ? $_GET[$eachRequestedElement] : NULL;
                break;       
        }
    }

    /**
     * Will return all the values of requested input type (i.e _POST or _GET) as is.
     * @return array An array of _GET or _POST variable values will return.
     */
    private function getAllInputValuesOfSelectedType()
    {
        switch($this->type){
            case self::TYPE_POST:
                return $_POST;
                break;
            case self::TYPE_GET:
                return $_GET;
                break;       
        }

    }
}