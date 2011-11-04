<?php
/**
 * More info: http://www.kavoir.com/2011/10/php-crontab-class-to-add-and-remove-cron-jobs.html
 * 
 * #######
 * In this class, array instead of string would be the standard input / output format.
 * Legacy way to add a job:
 * $output = shell_exec('(crontab -l; echo "'.$job.'") | crontab -');
 * #######
 */
namespace root\library\Crontab\index;

class Crontab {


    private function stringToArray($jobs = '') 
    {
        $array = explode("\r\n", trim($jobs)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }

    private function arrayToString($jobs = array()) 
    {
        $string = implode("\r\n", $jobs);
        return $string;
    }

    public function getJobs() 
    {
        $output = shell_exec('crontab -l');
        return $this->stringToArray($output);
    }

    public function saveJobs($jobs = array()) 
    {
        $output = shell_exec('echo "' . $this->arrayToString($jobs) . '" | crontab -');
        return $output;
    }

    public function doesJobExist($job = '') 
    {
        $jobs = $this->getJobs();
        if (in_array($job, $jobs)) {
            return true;
        } else {
            return false;
        }
    }

    public function addJob($job = '') 
    {
        if ($this->doesJobExist($job)) {
            return false;
        } else {
            $jobs = $this->getJobs();
            $jobs[] = $job;
            return $this->saveJobs($jobs);
        }
    }

    public function removeJob($job = '') 
    {
        if ($this->doesJobExist($job)) {
            $jobs = $this->getJobs();
            unset($jobs[array_search($job, $jobs)]);
            return $this->saveJobs($jobs);
        } else {
            return false;
        }
    }

}