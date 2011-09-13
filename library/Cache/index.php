<?php
namespace root\library\Cache\index;

final class Cache {

    private $fileExtension = '.txt', $hashFileName = true, $cacheFolderPath, $cacheFolderName = 'cache', $bufferContent, $hasBufferStarted = false;
    
    /**
     * This construct method makes our cache Folder Path and puts it in a proper property ($cacheFolderPath).
     * Instead of using the constant.
     */
    public function __construct() {
        $this->cacheFolderPath = FILE_PATH .'__rfolder' . DS . $this->cacheFolderName. DS;
        return;
    }

    /**
     * 	Our Main method that we will use it, and it will save our content in a file as a cache file,
     *  Then we can reuse it another times.
     * 
     * 	@param string $name Name of the caching file. Has to be unique since you'll pull your data from the cache engine with this variable.
     *  @param string $groupName A group name for the file name, With specifying a group and setting it to filename we can be able to have more control for our cache files.
     *  for example to delete files in a same time we can give a group name and then delete all files that have same group names.
     * 	@param mixed $content Can be any type of variable that you want to be cached.
     * 	@param int $duration Time you want to keep the content cached (in SECONDS). If $duration = 0, then the cache file will be saved for ever until you delete it manualy.
     * 	@return bool Either Simply returns true if the process has been successful., or returns false if could not cache the contents.
     */
    public function cacheContent($name, $groupName, $content, $duration = 3600) {
        global $registry;
        $this->createCacheFolder();
        $filename = $this->cacheFolderPath . $this->encryptName($name, $groupName);

        $content = array(
            'duration' => $duration,
            'creationTime' => time(),
            'content' => $content
        );
        $content = serialize($content);

        $handle = fopen($filename, 'w+');
        $result = fwrite($handle, $content);
        fclose($handle);

        if (!strlen($result)) {
            $registry->error->reportError('An Error occured while caching the content!!', __LINE__, __METHOD__, true);
            return;
        } else {
            return 1;
        }
    }

    /**
     * This method are going to create our cache directory where our cache files will be stored.
     * @global object $registry , The object of Registry Class.
     */
    private function createCacheFolder() {
        if (!file_exists($this->cacheFolderPath) or !is_dir($this->cacheFolderPath)) {
            global $registry;
            
            if(is_writable(dirname($this->cacheFolderPath))){
                chdir(dirname($this->cacheFolderPath));
                mkdir($this->cacheFolderPath);
            }else{
                $registry->error->reportError('The Cache Directory Is Not Writeable,
                Please Change The Cache Directory\'s Permission To 777, Then Refresh The Page And Then Change It To 755 (Do Not Forget It, Its neccesary
                 To Change It Back To 755 Again, OtherWise It Can Be Security Issue)<br />
                 Cache Directory Path: <strong>(' . dirname($this->cacheFolderPath) . ')</strong>', __LINE__, __METHOD__, true);
                return;
            }
            
            return 1;
        }
    }

    /**
     * 	Retrieving the content previously cached with cacheContent() method.
     * 	@param string $name Has to be the same name as used when calling cacheContent() method.
     *  @param string $groupName has to be the same name as used when calling cacheContent() method.
     * 	@return bool False if the cache has expired or doesn't exist, otherwise cache content will be returned.
     */
    public function getCache($name, $groupName) {
        global $registry;
        $filename = $this->cacheFolderPath . $this->encryptName($name, $groupName);
        
        if(!file_exists($filename)){
            $registry->error->reportError('Requested Cache File Does Not Exists.', __LINE__, __METHOD__);
            return;
        }
        
        $content = file_get_contents($filename);
        $content = unserialize($content);

        if($content['duration'] == 0){
            return $content['content'];
        }
        
        elseif(time() > $content['creationTime'] + $content['duration']){
            $this->deleteCache($name, $groupName);
            return 0;
        }
        
        else{
            return $content['content'];
        }
    }

    /**
     * Starts to save all stuffs in buffer.
     * @return boalen, true if it's done successfuly, else false will be returned.
     */
    public function startBuffer() {
        
        if (!$this->hasBufferStarted) {
            ob_start();
            $this->hasBufferStarted = true;
            return 1;
        }
        
        return 0;
    }

    /**
     * Stops and cache the bufffer, then returning the buffered content.
     * @param string $name, A name for cache file.
     * @param string $groupName, A group name for cache file.
     * @param int $duration, A limited time for our cache file to stop using it if the giving time is passed.
     * @return mixed, it returns the buffered stuffs (The contents) if it was succeed, otherwise it will return false.
     */
    public function cacheBuffer($name, $groupName, $duration = 3600) {
        
        if ($this->hasBufferStarted === true) {
            $this->bufferContent = ob_get_clean();
            $this->hasBufferStarted = false;
            $this->cacheContent($name, $groupName, $this->bufferContent, $duration);
            return $this->bufferContent;
        }
        
        return 0;
    }

    /**
     * 	Delete a cache file. Usually not used unless you create a cache file with $duration = 0 and want to regenerate the cache.
     * 	@param string $name Name of the caching file.
     *  @param string $groupName Group name of the file.
     * 	@return int False if file doesn't exist, true if the process has been successed.
     */
    public function deleteCache($name, $groupName) {
        $filename = $this->cacheFolderPath . $this->encryptName($name, $groupName);
        
        if (!file_exists($filename)) {
            return 0;
        }
        
        else {
            unlink($filename);
            return 1;
        }
    }

    /**
     * This method let's us to remove a group of files, the cache files will be detected using their group name that their are cached!
     * @global object $registry, let's us to access Registry class that can been able to use other classes too .
     * @param string $groupName, The group name of the cache file to detect and remove those files at once .
     * @return int, it will return 1 in success, otherwise it will return 0 .
     */
    public function deleteAGroup($groupName) {
        global $registry;
        $toSearch = $this->encryptName('', $groupName);
        
        foreach (glob("{$this->cacheFolderPath}*{$toSearch}") as $file) {
            
            if (!unlink($file)) {
                $registry->error->reportError('The Cache file ( ' . $file . ' ) Could not be removed', __LINE__, __METHOD__);
                return 0;
            }
            
        }
        return 1;
    }

    /**
     *  We can set our cache files extention through this method.
     * @param string $extension
     * @return int, 1 to show us it's done.
     */
    public function setFileExtension($extension) {
        $this->fileExtension = $extension;
        return 1;
    }

    /**
     * To get the file extension we can use this method.
     * @return string , cache file extention.
     */
    public function getFileExtension() {
        return $this->fileExtension;
    }

    /**
     * This method let's us to define the cache folder name to it's propper property.
     * @param string $folderName
     * @return bool, 1 to show us it's done.
     */
    public function setCacheFolderName($folderName) {
        $this->cacheFolderName = $folderName;
        return 1;
    }
    
    /**
     * Thorugh this method we are able to get cache folde name.
     * @return string  
     */
     public function getCacheFolderName() {
        return $this->cacheFolderName;
    }

    /**
     *  We can get the cache directory's absolute path through this method.
     * @return string , cache directory's path.
     */
    public function getCacheFolderPath() {
        return $this->cacheFolderPath;
    }

    /**
     * With this method we can tell class to hash (md5) cache file names or not.
     * @param boolean $value, true then file name will be md5()ed, false then it won't.
     * @return bool, 1 to show us it's done.
     */
    public function setHashFileName($value) {
        $this->hashFileName = $value;
        return 1;
    }

    /**
     * This method will return us true of it's going to hash (md5) our cache file names, or false if it's not.
     * @return bool , true or false.
     */
    public function getHashFileName() {
        return $this->hashFileName;
    }

    /**
     * 	Encrypt the name of a given variable to avoid malformed files.
     * 	@param string $name Cache variable name.
     *  @param string $groupName group name of cache file.
     * 	@access private
     */
    private function encryptName($name, $groupName) {
        if ($this->hashFileName == TRUE){
            return md5($name) . '_' . md5($groupName) . $this->fileExtension;
        }

        elseif ($this->hashFileName == FALSE){
            return $name . '_' . $groupName . $this->fileExtension;
        }
    }

}