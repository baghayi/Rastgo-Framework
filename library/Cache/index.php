<?php
namespace root\library\Cache\index;

class Cache {

    private $fileExtension = '.txt', $hashFileName = true, $cacheFolderPath = CACHE_MAIN_DIR, $bufferContent, $hasBufferStarted = false;

    /**
     * 	Our Main method that we will use it, and it will save our content in a file as a cache file,
     *  Then we can reuse it another times,
     * 
     * 	@param string $name Name of the caching file. Has to be unique since you'll pull your data from the cache engine with this variable.
     *  @param string $groupName A group name for the file name, With specifying a group and setting it to filename we can be able to have more control for our cache files, 
     *  for example to delete files in a same time we can give a group name and then delete all files that have same group names
     * 	@param mixed $content Can be any type of variable that you want to be cached.
     * 	@param int $duration Time you want to keep the content cached (in SECONDS). If $duration = 0, then the cache file will be saved for ever until you delete it manualy
     * 	@return bool Either Simply returns true if the process has been successful., or returns false if could not cache the contents
     */
    public function cacheContent($name, $groupName, $content, $duration = 3600) {
        global $registry;
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
            return false;
        } else {
            return true;
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
        if (!file_exists($filename)) {
            $registry->error->reportError('Requested Cache File Does Not Exists.', __LINE__, __METHOD__);
            return false;
        }
        $content = file_get_contents($filename);
        $content = unserialize($content);

        if ($content['duration'] == 0) {
            return $content['content'];
        } elseif (time() > $content['creationTime'] + $content['duration']) {
            $this->deleteCache($name, $groupName);
            return false;
        } else {
            return $content['content'];
        }
    }
    
    /**
     * Starts to save all stuffs in buffer
     * @return boalen, true if it's done successfuly, else false will be returned 
     */
    public function startBuffer() {
        // we don't need to handle multiple buffers for simple content caching
        if (!$this->hasBufferStarted) {
            ob_start();
            $this->hasBufferStarted = true;
            return true;
        }
        return false;
    }

/**
 * Stops and cache the bufffer, then returning the buffered content,
 * @param string $name, A name for cache file
 * @param string $groupName, A group name for cache file
 * @param int $duration, A limited time for our cache file to stop using it if the giving time is passed,
 * @return mixed, it returns the buffered stuffs (The contents) if it was succeed, otherwise it will return false
 */
    public function cacheBuffer($name, $groupName, $duration = 3600) {
        if ($this->hasBufferStarted === true) {
            $this->bufferContent = ob_get_clean();
            $this->hasBufferStarted = false;
            $this->cacheContent($name, $groupName, $this->bufferContent, $duration);

            return $this->bufferContent;
        }
        return false;
    }

    /**
     * 	Delete a cache file. Usually not used unless you create a cache file with $duration = 0 and want to regenerate the cache.
     * 	@param string $name Name of the caching file.
     *  @param string $groupName Group name of the file
     * 	@return bool False if file doesn't exist, true if the process has been successed.
     */
    public function deleteCache($name, $groupName) {
        $filename = $this->cacheFolderPath . $this->encryptName($name, $groupName);
        if (!file_exists($filename)) {
            return false;
        } else {
            unlink($filename);
            return true;
        }
    }
    
    public function deleteAGroup($groupName){
        
    }

    public function setFileExtension($extension) {
        $this->fileExtension = $extension;
        return;
    }

    public function setCacheFolderPath($folderAddress) {
        $this->cacheFolderPath = $folderAddress;
        return;
    }

    public function setHashFileName($value) {
        $this->hashFileName = $value;
        return;
    }

    /**
     * 	Encrypt the name of a given variable to avoid malformed files.
     * 	@param string $name Cache variable name
     *      @param string $groupName group name of cache file
     * 	@access private
     */
    private function encryptName($name, $groupName) {
        if ($this->hashFileName == TRUE) {
            return md5($name) . '_' . md5($groupName) . $this->fileExtension;
        } elseif ($this->hashFileName == FALSE) {
            return $name . '_' . $groupName . $this->fileExtension;
        }
    }

}