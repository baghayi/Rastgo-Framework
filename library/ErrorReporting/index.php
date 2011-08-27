<?php
namespace root\library\ErrorReporting\index;

final class ErrorReporting {
    /**
     * With this property we want to seperate the error messages in files instead of putting all error messages toghether in a one file,
     * And this array are going to be equaled with one parameter of the reportError() method,
     * @errorTypes array
     */
    private $errorTypes = array('database', 'authentication', 'others');
    private $logFileExtention = '.txt';
    /**
     *  This property is going to contain the complete error, includes error line, error method, can cantain IP, the main message, ...
     *  To log it and or even throw it as a exception
     * @reportedError string 
     */
    private $reportedError;
    /**
     * The size of the each log files, A limitation for Log Files
     * The number is in byte,
     * And by default it's 10 M or 10000000 bytes
     * @var integer  $logFileSize
     */
    private $logFileSize = 10000000;

    /**
     *  This is the main method that we can report errors with it,
     *  And then the class will do the other thing itself
     * @param string $message
     * @param integer $line
     * @param string $methodName
     * @param boolean $throwException
     * @param string $errorType 
     */
    public function reportError($message, $line, $methodName, $throwException = FALSE, $errorType = 'others') {
        $TodaysDate_Time = strftime("%c");
        $userIPAddress = $_SERVER['REMOTE_ADDR'];
        $referedPlace = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '---';
        $this->reportedError = "- {$TodaysDate_Time} | {$userIPAddress} | Error Message: {$message}, At line: {$line}, In Method: {$methodName}, The Referer Address: {$referedPlace} . \r\n";
        $this->spliteLogFile($errorType);
        $this->WriteInLogFile($errorType);
        if ($throwException === TRUE)
            $this->throwException($message);
    }

    private function throwException($message) {
        throw new \Exception($message);
    }

    /**
     * With this method we are wrting the error message in the log file
     * @param string $fileName 
     */
    private function WriteInLogFile($fileName) {
        /**
         * Create And check whether log files are created or Not!\
         * If there is any problem it would be thrown an exception.
         */
        $this->createLogFiles();
        $couldNotWriteMessage = 'Reported Message Could Not Be Written In The Log File!';
        $fileAddress = LOG_FOLDER_PATH .$fileName . DS . $fileName . $this->logFileExtention;
        if (!($handle = fopen($fileAddress, 'a'))) {
            $fileCanNotBeOpenedMessage = 'The Log File Is Not Able To Be Opened!';
            $this->throwException($fileCanNotBeOpenedMessage);
        }
        if (FALSE === fwrite($handle, $this->reportedError))
            $this->throwException($couldNotWriteMessage);

        fclose($handle);
    }

    /**
     * With this method we are creating the Log files,
     */
    private function createLogFiles() {
        $dirNotWritableMessage = 'The Log Directory Is Not Writable, Please Change Its Permission To 777 And Then Refresh The Page,<br /> If You Did Not Get Any Messages Like This Again, Change That File\'s Permission to 755, <br /> Directory Address: <strong> ' . LOG_FOLDER_PATH . ' </strong>';
        foreach ($this->errorTypes as $fileName) {
            /**
             * Checing for directories,
             */
            if(!file_exists(LOG_FOLDER_PATH . $fileName) or !is_dir(LOG_FOLDER_PATH . $fileName)){
                if(is_writable(LOG_FOLDER_PATH))
                    mkdir(LOG_FOLDER_PATH . $fileName);
                else
                    $this->throwException($dirNotWritableMessage);                    
            }
            
            /**
             * Checking for log files,
             */
            $fileAddress = LOG_FOLDER_PATH . $fileName . DS . $fileName . $this->logFileExtention;
            if (!file_exists($fileAddress)) {
                if (is_writable(LOG_FOLDER_PATH . $fileName)) {
                    $fileHandle = fopen($fileAddress, 'a');
                    fclose($fileHandle);
                } else {
                    $dirNotWritableMessageInside = 'The Log Directory Is Not Writable, Please Change Its Permission To 777 And Then Refresh The Page,<br /> If You Did Not Get Any Messages Like This Again, Change That Folder\'s Permission to 755, <br /> Directory Address: <strong> ' . LOG_FOLDER_PATH .$fileName. ' </strong>';
                    $this->throwException($dirNotWritableMessageInside);
                }
                $this->checkingLogFiles($fileAddress);
            }
        }
    }

    /**
     * After creating log files, with this method we are going to see whether those files are created or Not,
     * If not then an exception will be thrown!
     */
    private function checkingLogFiles($fileAddress) {
        $fileDoesNotExistsMessage = 'This File Could Not Have Been Created!: <br />' . $fileAddress;
        if (!file_exists($fileAddress))
            $this->throwException($fileDoesNotExistsMessage);
        else
            return TRUE;
    }

    public function setLogFileExtension($extension) {
        $this->logFileExtention = $extension;
    }

    /**
     * This method can add new error types to ErrorTypes array
     * Error Types are file names that reported errors will be stored in them!
     * @param string $errorType 
     */
    public function addErrorType($errorType) {
        $this->errorTypes[] = $errorType;
    }

    /**
     * This methid can return us the Error types (file names) whicj errors will be stored in them, as an array
     * @return array
     */
    public function showErrorTypes() {
        return $this->errorTypes;
    }

    private function spliteLogFile($fileName) {
        if(file_exists(LOG_FOLDER_PATH . $fileName .DS .$fileName. $this->logFileExtention)) {
            $fileAbsolutePath = LOG_FOLDER_PATH . $fileName .DS.$fileName . $this->logFileExtention;
            $fileSize = filesize($fileAbsolutePath);
            $date = strftime("%Y_%m_%d_%H_%M_%S", time());
            $fileNewAbsolutePath = LOG_FOLDER_PATH . $fileName .DS. $date . '__' . $fileName . $this->logFileExtention;
            if ($fileSize >= $this->logFileSize) {
                if (false === rename($fileAbsolutePath, $fileNewAbsolutePath))
                    $this->reportError('The Log File Could Not Be Renamed!', __LINE__, __METHOD__,false);
                else
                    return false;
            }
        }
    }
    
    /**
     *  With this method we are able to set/change the file limitation size,
     *  Remember that it must be an integer and it must be in bytes!
     * @param integer $fileSize 
     */
    public function setFileSize($fileSize){
        $this->logFileSize = (int)$fileSize;
    }

}