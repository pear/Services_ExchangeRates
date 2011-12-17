<?php
/**
 * PHP Version 5
 *
 * @package   Services_ExchangeRates
 * @author    Marshall Roch <marshall@exclupen.com>
 * @author    Colin Ross <cross@php.net>
 * @author    Daniel O'Connor <daniel.oconnor@gmail.com>
 * @copyright Copyright 2003 Marshall Roch
 * @license   http://www.php.net/license/2_02.txt PHP License 2.0
 */

/**
 * Cache_Lite is needed to cache the feeds
 */
require_once 'Cache/Lite.php';
require_once 'HTTP/Request2.php';
require_once 'Services/ExchangeRates/Exception.php';

class Services_ExchangeRates_Transport_HTTP_Cached 
{
    var $cache;
    protected $request;

    public function __construct(Cache_Lite $cache, HTTP_Request2 $request) 
    {
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
    *
    * @return string File contents
    */
    function fetch($url) 
    {
        $cacheID = md5($url);
                                        
        if ($data = $this->cache->get($cacheID)) {
            return $data;
        }
               
        // if $cache->get($cacheID) found the file, but it was expired, 
        // $cache->_file will exist 
        if (isset($this->cache->_file) && file_exists($this->cache->_file)) {
            $this->request->setHeader(
                'If-Modified-Since',
                gmdate("D, d M Y H:i:s",
                filemtime($this->cache->_file)) ." GMT"
            );
        }
        
        $response = $this->request->send();
        
        if (!($this->response->getStatus() == 304)) {
            // data is changed, so save it to cache
            $data = $response->getBody();
            $this->cache->save($data, $cacheID);

            return $data;
        }

        // retrieve the data, since the first time we did this failed
        if ($data = $this->cache->get($cacheID, 'default', true)) {
            return $data;
        }
        
        throw new Services_ExchangeRates_Exception(
            "Unable to retrieve file ${url} (unknown reason)",
            SERVICES_EXCHANGERATES_ERROR_RETRIEVAL_FAILED
        );
    }
}
