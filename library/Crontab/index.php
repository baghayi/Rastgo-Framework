<?php
namespace root\library\Crontab\index;

class Crontab {
    private $CrontabCommandObject = NULL;
    
    /**
     * By calling this method we will be received an object of the CrontabCommand class of will be able to use it to access CrontabCommand class methods.
     * @return object Will return object of CrontabCommand Class.
     */
    public function makeCommand() 
    {
        if($this->CrontabCommandObject === NULL)
        {
            $this->CrontabCommandObject = new \root\library\Crontab\CrontabCommand\CrontabCommand();
        }
        
        return $this->CrontabCommandObject;
    }

    /**
     * Using this method we can see our list of cron jobs.
     * @return array List of cron jobs.
     */
    public function getJobs() 
    {
        $output = shell_exec('crontab -l');
        return $this->stringToArray($output);
    }

    /**
     * This method save cron jobs as an array input in the system.
     * Be aware that if you use this method directly it will cause other cron jobs to be omitted! (therefore before saving you can get cron jobs that are in system then add yours and save them using this method in system.).
     * @param array $jobs Cron job command(s).
     * @return mixed, Even on success it will return null!, on failure ???? 
     */
    public function saveJobs($jobs = array()) 
    {
        $output = shell_exec('echo "' . $this->arrayToString($jobs) . '" | crontab -');
        return $output;
    }

    /**
     * Using the method we can see whether the specified cron jon exists or not.
     * @param string $job The cron job (as it was inserted).
     * @return boolean False on failure, True on success.
     */
    public function doesJobExist($job = '') 
    {
        $jobs = $this->getJobs();
        
        if (!in_array($job, $jobs)) 
        {
            return 0;
        }
        
        return 1;
    }

    /**
     * This method lets us to add our cron jobs to the system.
     * The template of the cron job (input): "Minute Hour  DayOfMonth Month DayOfWeek   Command" .
     * @param string $job The Cronjob in above style.
     * @return mixed false on failure, null on success!
     */
    public function addJob($job = '') 
    {
        if ($this->doesJobExist($job)) 
        {
            return 0;
        }
        
        $jobs = $this->getJobs();
        $jobs[] = $job;
        return $this->saveJobs($jobs);
    }

    /**
     * Using this method we can remove our cron job form the system.
     * @param string $job The cron job to be removed.
     * @return mixed, false on failure, Null on success!
     */
    public function removeJob($job = '') 
    {
        if (!$this->doesJobExist($job)) 
        {
            return 0;
        }
        
        $jobs = $this->getJobs();
        unset($jobs[array_search($job, $jobs)]);
        return $this->saveJobs($jobs);
    }
    
    /**
     * This method converts the string given to an array.
     * @param string $jobs Cron Job(s) as an string.
     * @return array Cron job(s) as an array.
     */
    private function stringToArray($jobs = '') 
    {
        /**
         * trim() gets rid of the last \r\n
         */
        $array = explode("\r\n", trim($jobs));
        
        foreach ($array as $key => $item) 
        {
            if ($item == '') 
            {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * This method converts the array of cron jobs to string.
     * @param array $jobs Cron job(s) as an array.
     * @return string Cron job(s) as an string.
     */
    private function arrayToString($jobs = array()) 
    {
        $string = implode("\r\n", $jobs);
        return $string;
    }
    
    public function __destruct() {
        $this->CrontabCommandObject = NULL;
    }
}