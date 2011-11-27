<?php
namespace root\library\ErrorReporting\index;

final class ErrorReporting {
    /**
     * With this property we want to seperate the error messages in files instead of putting all error messages toghether in a one file.
     * And this array are going to be equaled with one parameter of the reportError() method.
     * @errorTypes array
     */
    private $errorTypes = array('database', 'authentication', 'others');
    private $logFileExtention = '.txt', $logDirPath;
    /**
     *  This property is going to contain the complete error, includes error line, error method, can cantain IP, the main message, ... .
     *  To log it and or even throw it as a exception.
     * @reportedError string 
     */
    private $reportedError;
    /**
     * The size of the each log files, A limitation for Log Files.
     * The number is in byte.
     * And by default it's 10 M or 10000000 bytes .
     * @var integer  $logFileSize
     */
    private $logFileSize = 10000000;
    
    public function __construct() {
        $this->logDirPath = FILE_PATH . '__rfolder' . DS . 'logs' . DS;
        return;
    }

    /**
     *  This is the main method that we can report errors with it.
     *  And then the class will do the other thing itself.
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
        
        if ($throwException == TRUE){
            $this->throwException($message);
            return 1;
        }

        return 1;
    }

    private function throwException($message) {
        throw new \Exception($message);
        return;
    }

    /**
     * With this method we are wrting the error message in the log file.
     * @param string $fileName 
     */
    private function WriteInLogFile($fileName) {
        $this->createLogDir();
        $this->createLogFolders();
        $fileAddress = $this->logDirPath . $fileName . DS . $fileName . $this->logFileExtention;
        
        if (!($handle = fopen($fileAddress, 'a'))) {
            $this->throwException('The Log File Is Not Able To Be Opened!');
            return;
        }
        
        if (FALSE === fwrite($handle, $this->reportedError)){
            $this->throwException('Reported Message Could Not Be Written In The Log File!');
            return;
        }
        
        fclose($handle);
        return 1;
    }

    /**
     * With this method we are creating the Log Folders, To be sure they are exists or not.
     */
    private function createLogFolders() {
        
        foreach ($this->errorTypes as $fileName) {
            
            if(!file_exists($this->logDirPath . $fileName) or !is_dir($this->logDirPath . $fileName)){
                
                if(is_writable($this->logDirPath)){
                    mkdir($this->logDirPath . $fileName);
                }
                
                else{
                    $this->throwException('The Log Directory Is Not Writable, Please Change Its Permission To 777 And Then Refresh The Page,<br /> If You Did Not Get Any Messages Like This Again, Change That File\'s Permission to 755, <br /> Directory Address: <strong> ' . $this->logDirPath . ' </strong>');
                    return;
                }
            }
        }
        return 1;
    }

    public function setLogFileExtension($extension) {
        $this->logFileExtention = $extension;
        return 1;
    }

    /**
     * This method can add new error types to ErrorTypes array.
     * Error Types are file names that reported errors will be stored in them!
     * @param string $errorType 
     */
    public function addErrorType($errorType) {
        $this->errorTypes[] = $errorType;
        return 1;
    }

    /**
     * This methid can return us the Error types (file names) whicj errors will be stored in them, as an array.
     * @return array
     */
    public function showErrorTypes() {
        return $this->errorTypes;
    }

    private function spliteLogFile($fileName) {
        
        if(file_exists($fileAbsolutePath = $this->logDirPath . $fileName . DS . $fileName . $this->logFileExtention)) {
            $fileNewAbsolutePath = $this->logDirPath . $fileName . DS . strftime("%Y_%m_%d_%H_%M_%S", time()) . '__' . $fileName . $this->logFileExtention;
            
            if (filesize($fileAbsolutePath) >= $this->logFileSize) {
                
                if (false === rename($fileAbsolutePath, $fileNewAbsolutePath)){
                    $this->reportError('The Log File Could Not Be Renamed!', __LINE__, __METHOD__,false);
                    return;
                }
            }
        }
        return 1;
    }
    
    /**
     *  With this method we are able to set/change the file limitation size.
     *  Remember that it must be an integer and it must be in bytes!
     * @param integer $fileSize 
     */
    public function setFileSize($fileSize){
        $this->logFileSize = (int)$fileSize;
        return 1;
    }
    
    private function createLogDir(){
        
        if(!file_exists($this->logDirPath) || !is_dir($this->logDirPath)){
            chdir( FILE_PATH .'__rfolder');
            
            if(is_writable(dirname($this->logDirPath))){
                mkdir($this->logDirPath);
                return 1;
            }
            
            else{
                $this->throwException('The __rfolder Directory Is Not Writable, Please Change Its Permission To 777 And Then Refresh The Page,<br /> If You Did Not Get Any Messages Like This Again, Change That File\'s Permission to 755, <br /> Directory Address: <strong> ' . dirname($this->logDirPath) . ' </strong>');
                    return;
            }
        }
        return;
    }

}