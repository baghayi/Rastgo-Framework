<?php
use root\core\baseController\baseController;

class crontabController extends baseController {
    
    public function index() 
    {
        $url = self::$registry->request->go("crontab", null, null, true);
        
        $html = <<<_VIEW
        <ul>
            <li><a href="{$url}makingCronCommand">makingCronCommand<a/></li>
            <li><a href="{$url}addingNewjob">addingNewjob<a/></li>
            <li><a href="{$url}removingTheJob">removingTheJob<a/></li>
            <li><a href="{$url}checkingIfJobExists">checkingIfJobExists<a/></li>
            <li><a href="{$url}listOfJobs">listOfJobs<a/></li>
            <li><a href="{$url}savingJobsManually">savingJobsManually<a/></li>
        </ul>
        
_VIEW;
            
            echo $html;
            return;
    }
    
    public function makingCronCommand() 
    {
        $cron = new root\library\Crontab\index\Crontab();

        /**
         * Seting Command with default values (times default values).
         */
        $cron->makeCommand()->setCommand('php -f /var/www/RastgoFramework/index.php');
        echo $cron->makeCommand()->getResult();
        echo '<br />';
        
        /**
         * Making command with defining time values.
         */
        $cron->makeCommand()->setCommand('php -f /var/www/RastgoFramework/index.php');
        $cron->makeCommand()->setMinute(array(59, 50, 30, 40, 0));
        $cron->makeCommand()->setHour(0);
        $cron->makeCommand()->setDayOfMonth(array(30, 31, 10, 1));
        $cron->makeCommand()->setMonth(array(1, 2, 3, 4, 5));
        $cron->makeCommand()->setDayOfWeek(array(5, 4, 3, 6));
        echo $cron->makeCommand()->getResult();
    }
    
    public function addingNewjob() 
    {
        $cron = new root\library\Crontab\index\Crontab();
        
        echo '<br /><pre>';
        var_dump( $cron->addJob('30 15 * * * php -f /var/www/RastgoFramework/'));
        echo '</pre><br />';
    }
    
    public function removingTheJob() 
    {
        $cron = new root\library\Crontab\index\Crontab();
        
        echo '<br /><pre>';
        var_dump($cron->removeJob('30 15 * * * php -f /var/www/RastgoFramework/'));
        echo '</pre><br />';
    }
    
    public function checkingIfJobExists() 
    {
        $cron = new root\library\Crontab\index\Crontab();
        
        echo '<br /><pre>';
        var_dump($cron->doesJobExist('30 15 * * * php -f /var/www/RastgoFramework/'));
        echo '</pre><br />';
    }
    
    public function listOfJobs() 
    {
        $cron = new root\library\Crontab\index\Crontab();
        
        echo '<br /><pre>';
        var_dump($cron->getJobs());
        echo '</pre><br />';
    }
    
    /**
     * This will cause other jobs to be omitted, if you donot get them and insert them with the new jobs!
     */
    public function savingJobsManually() 
    {
        $cron = new root\library\Crontab\index\Crontab();
        
        echo '<br /><pre>';
        var_dump($cron->saveJobs(array(
            '33 12 * * * php -f /var/www/RastgoFramework/index.php'
        )));
        echo '</pre><br />';
    }
    
}
