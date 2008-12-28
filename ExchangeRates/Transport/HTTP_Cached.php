<?php
/**
 * Cache_Lite is needed to cache the feeds
 */
require_once 'Cache/Lite.php';
include_once 'HTTP/Request.php';

class Services_ExchangeRates_Transport_HTTP_Cached {
    var $cache;

    function Services_ExchangeRates_Transport_HTTP_Cached($cache, $request) {
        if (!is_object($cache)) {
            $cache = new Cache_Lite();
        }

        if (!is_object($request)) {
            $request = new HTTP_Request();
        }

        $this->cache = $cache;
        $this->request = $request;
    }

   /**
    * Retrieves data from cache, if it's there.  If it is, but it's expired, 
    * it performs a conditional GET to see if the data is updated.  If it 
    * isn't, it down updates the modification time of the cache file and 
    * returns the data.  If the cache is not there, or the remote file has been
    * modified, it is downloaded and cached.
    *
    * @param string URL of remote file to retrieve
    * @return string File contents
    */
    function fetch($url) {
        $cacheID = md5($url);
                                        
        if ($data = $this->cache->get($cacheID)) {
            return $data;
        }
               
        // if $cache->get($cacheID) found the file, but it was expired, 
        // $cache->_file will exist 
        if (isset($this->cache->_file) && file_exists($this->cache->_file)) {
            $this->request->addHeader('If-Modified-Since', gmdate("D, d M Y H:i:s", filemtime($this->cache->_file)) ." GMT");
        }
        
        $this->request->sendRequest();
        
        if (!($this->request->getResponseCode() == 304)) {
            // data is changed, so save it to cache
            $data = $this->request->getResponseBody();
            $this->cache->save($data, $cacheID);

            return $data;
        }

        // retrieve the data, since the first time we did this failed
        if ($data = $this->cache->get($cacheID, 'default', true)) {
            return $data;
        }
        
        Services_ExchangeRates::raiseError("Unable to retrieve file ${url} (unknown reason)", SERVICES_EXCHANGERATES_ERROR_RETRIEVAL_FAILED);

        return false;
    }
}
