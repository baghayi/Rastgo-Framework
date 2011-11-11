<?php
namespace root\library\Crontab\CrontabCommand;

class CrontabCommand
{
    private $minute = '*', $hour = '*', $dayOfMonth = '*', $month = '*', $dayOfWeek = '*', $command = NULL;
    private $registry = NULL;
    
    /**
     * This construct class is just bringing the Registry class object to this class, to use it in the class methods.
     * @global object $registry The object of the Registry Class.
     * @return void
     */
    public function __construct() 
    {
        global $registry;
        $this->registry = $registry;
        
        return;
    }
    
    /**
     * Using this method we can define the 'Minute' part of the cron job command.
     * @param array|int $input It can be as an array like array(1, 2, 3 , ...), or just a number: 22 .
     * @return string The information that has inserted as string. 
     */
    public function setMinute($input)
    {
        $inputAsArray = array();
        
        if(!is_array($input))
        {
            $inputAsArray[] = (int)$input;
        }
        else
        {
            $inputAsArray = $input;
        }
        
        /**
         * Checking The Conditions.
         */
        foreach($inputAsArray as $value)
        {
            if(($value < 0) || ($value > 59))
            {
                $this->registry->error->reportError('The Minute can not be lower that zero (0) or more that fifty-nine (59).', __LINE__, __METHOD__, true);
            }
        }
        
        $this->minute = implode(',', $inputAsArray);
        
        return $this->minute;
    }
    
    /**
     * Using this method we can define the 'Hour' part of the cron job command.
     * @param array|int $input It can be as an array like array(1, 2, 3 , ...), or just a number: 22 .
     * @return string The information that has inserted as string. 
     */
    public function setHour($input)
    {
        $inputAsArray = array();
        
        if(!is_array($input))
        {
            $inputAsArray[] = $input;
        }
        else
        {
            $inputAsArray = $input;
        }
         
        /**
         * Checking The Conditions.
         */
        foreach($inputAsArray as $value)
        {
            if(($value < 0) || ($value > 23))
            {
                $this->registry->error->reportError('The Hour can not be lower that zero (0) or more that twenty-three (23).', __LINE__, __METHOD__, true);
            }
        }
        
        
        $this->hour = implode(',', $inputAsArray);
        
        return $this->hour;
    }

    /**
     * Using this method we can define the 'Day of Month' part of the cron job command.
     * @param array|int $input It can be as an array like array(1, 2, 3 , ...), or just a number: 22 .
     * @return string The information that has inserted as string. 
     */
    public function setDayOfMonth($input)
    {
        $inputAsArray = array();
        
        if(!is_array($input))
        {
            $inputAsArray[] = $input;
        }
        else
        {
            $inputAsArray = $input;
        }
        
        /**
         * Checking the Conditions.
         */
        foreach($inputAsArray as $value)
        {
            if(($value < 1) || ($value > 31))
            {
                $this->registry->error->reportError('The Day Of Month can not be lower that one (1) or more that thirty-one (31).', __LINE__, __METHOD__, true);
            }
        }
        
        
        $this->dayOfMonth = implode(',', $inputAsArray);
        
        return $this->dayOfMonth;
    }

    /**
     * Using this method we can define the 'Month' part of the cron job command.
     * @param array|int $input It can be as an array like array(1, 2, 3 , ...), or just a number: 12 .
     * @return string The information that has inserted as string. 
     */
    public function setMonth($input)
    {
        $inputAsArray = array();
        
        if(!is_array($input))
        {
            $inputAsArray[] = $input;
        }
        else
        {
            $inputAsArray = $input;
        }
        
        /**
         * Checking the Conditions.
         */
        foreach($inputAsArray as $value)
        {
            if(($value < 1) || ($value > 12))
            {
                $this->registry->error->reportError('The Month can not be lower that one (1) or more that twelve (12).', __LINE__, __METHOD__, true);
            }
        }
        
        $this->month = implode(',', $inputAsArray);
        
        return $this->month;
    }

    /**
     * Using this method we can define the 'Day of Week' part of the cron job command.
     * @param array|int $input It can be as an array like array(1, 2, 3 , ...), or just a number: 5 .
     * @return string The information that has inserted as string. 
     */
    public function setDayOfWeek($input)
    {
        $inputAsArray = array();
        
        if(!is_array($input))
        {
            $inputAsArray[] = $input;
        }
        else
        {
            $inputAsArray = $input;
        }
        
        /**
         * Checking the Conditions.
         */
        foreach($inputAsArray as $value)
        {
            if(($value < 0) || ($value > 6))
            {
                $this->registry->error->reportError('The Day Of Week can not be lower that Erone (0) or more that Six (6).', __LINE__, __METHOD__, true);
            }
        }
        
        
        $this->dayOfWeek = implode(',', $inputAsArray);
        
        return $this->dayOfWeek;
    }

    /**
     * Using this method we can set our command that want server to run it in the specified time. (the command itself).
     * @param string $input The command to be run by server.
     * @return string string The information that has inserted as string. 
     */
    public function setCommand($input)
    {
        $this->command = (string)$input;
        
        return $this->command;
    }
    
    /**
     * After setting everything (time and command) the eventually we can get the final code (comand including time and command itself) using thing method and then we can use it in the main Crontab class.
     * @return string The final command includeing time and command itself will be returned.
     */
    public function getResult() 
    {
        if($this->command === NULL)
        {
            $this->registry->error->reportError('The Command is not defined in CrontabCommand Class via this method: setCommand() ! Please set it first. ', __LINE__, __METHOD__, true);
            return;
        }
        
        $result = $this->minute . ' ' . $this->hour . ' ' . $this->dayOfMonth . ' ' . $this->month . ' ' . $this->dayOfWeek . ' ' . $this->command;
        $this->reset();
        
        return $result;
    }
    
    /**
     * Using this method we can reset every thing (times, commands) to their default value like the first time of using the object of the class, And then you will have to redefine them (times, and command).
     * @return void 
     */
    public function reset()
    {
        $this->minute = '*';
        $this->hour = '*';
        $this->dayOfMonth = '*';
        $this->month = '*';
        $this->dayOfWeek = '*';
        $this->command = NULL;
        
        return;
    }
    

}