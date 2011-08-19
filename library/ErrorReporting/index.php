<?php

namespace root\library\ErrorReporting\index;

final class ErrorReporting {

    /**
     * With this property we want to seperate the error messages in files instead of putting all error messages toghether in a one file,
     * And this array are going to be equaled with one parameter of the reportError() method,
     * @errorTypes array
     */
    private static $errorTypes = array('database', 'authentication', 'others');
    private static $logFileExtention = '.txt';
    /**
     *  This property is going to contain the complete error, includes error line, error method, can cantain IP, the main message, ...
     *  To log it and or even throw it as a exception
     * @reportedError string 
     */
    private static $reportedError;

    /**
     *  This is the main method that we can report errors with it,
     *  And then the class will do the other thing itself
     * @param string $message
     * @param integer $line
     * @param string $methodName
     * @param boolean $throwException
     * @param string $errorType 
     */
    public static function reportError($message, $line, $methodName, $throwException = FALSE, $errorType = 'others') {
        $TodaysDate_Time = strftime("%c");
        $userIPAddress = $_SERVER['REMOTE_ADDR'];
        $referedPlace = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '---';
        static::$reportedError = "- {$TodaysDate_Time} | {$userIPAddress} | Error Message: {$message}, At line: {$line}, \r\n\t In Method: {$methodName}, \r\n\t The Referer Address: {$referedPlace} . \r\n";
        static::WriteInLogFile($errorType);
        if ($throwException === TRUE)
            static::throwException($message);
    }

    private static function throwException($message) {
        throw new \Exception($message);
    }

    /**
     * With this method we are wrting the error message in the log file
     * @param string $fileName 
     */
    private static function WriteInLogFile($fileName) {
        /**
         * Create And check whether log files are created or Not!\
         * If there is any problem it would be thrown an exception.
         */
        static::createLogFiles();
        $couldNotWriteMessage = 'Reported Message Could Not Be Written In The Log File!';
        $fileAddress = LOG_FOLDER_PATH . $fileName . static::$logFileExtention;
        if(!($handle = fopen($fileAddress, 'a'))){
            $fileCanNotBeOpenedMessage = 'The Log File Is Not Able To Be Opened!';
            static::throwException($fileCanNotBeOpenedMessage);
        }
        if (FALSE === fwrite($handle, static::$reportedError))
            static::throwException($couldNotWriteMessage);

        fclose($handle);
        return TRUE;
    }

    /**
     * With this method we are creating the Log files,
     */
    private static function createLogFiles() {
        $dirNotWritableMessage = 'The Log Directory Is Not Writable, Please Change Its Permission To 777 And Then Refresh The Page,<br /> If You Did Not Get Any Messages Like This Change That File\'s Permission to 755, <br /> Directory Address: <strong> ' . LOG_FOLDER_PATH . ' </strong>';
        foreach (static::$errorTypes as $fileName) {
            $fileAddress = LOG_FOLDER_PATH . $fileName . static::$logFileExtention;
            if (!file_exists($fileAddress)) {
                if (!is_writable(LOG_FOLDER_PATH)) {
                    static::throwException($dirNotWritableMessage);
                } else {
                    $fileHandle = fopen($fileAddress, 'a');
                    fclose($fileHandle);
                }
                static::checkingLogFiles($fileAddress);
            }
        }
    }

    /**
     * After creating log files, with this method we are going to see whether those files are created or Not,
     * If not then an exception will be thrown!
     */
    private static function checkingLogFiles($fileAddress) {
        $fileDoesNotExistsMessage = 'This File Could Not Have Been Created!: <br />' . $fileAddress;
        if (!file_exists($fileAddress))
            static::throwException($fileDoesNotExistsMessage);
        else
            return TRUE;
    }

    public static function setLogFileExtension($extension) {
        static::$logFileExtention = $extension;
    }

    /**
     * This method can add new error types to ErrorTypes array
     * Error Types are file names that reported errors will be stored in them!
     * @param string $errorType 
     */
    public static function addErrorType($errorType) {
        static::$errorTypes[] = $errorType;
    }

    /**
     * This methid can return us the Error types (file names) whicj errors will be stored in them, as an array
     * @return array
     */
    public static function showErrorTypes() {
        return static::$errorTypes;
    }

}