<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at                              |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Marshall Roch <marshall@exclupen.com>                        |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * @author Marshall Roch <marshall@exclupen.com>
 * @copyright Copyright 2003 Marshall Roch
 * @license http://www.php.net/license/2_02.txt PHP License 2.0
 * @package Services_ExchangeRates
 */

require_once 'Services/ExchangeRates/Transport/Default.php';
require_once 'XML/Unserializer.php';
/**
 * Common functions for data retrieval
 *
 * Provides base functions to retrieve data feeds in different
 * formats.
 *
 * @package Services_ExchangeRates
 */
class Services_ExchangeRates_Common {

    var $transport;

    function Services_ExchangeRates_Common($transport) {
        if (!is_object($transport)) {
            $transport = new Services_ExchangeRates_Transport_Default();
        }

        $this->setTransport($transport);
    }

    function setTransport($transport) {
        $this->transport = $transport;
    }

   /**
    * Retrieves data
    *
    * @param string URL of remote file to retrieve
    * @return string File contents
    */
    function retrieveFile($url) {
        return $this->transport->fetch($url);
    }

   /**
    * Downloads XML file or returns it from cache
    *
    * @param string URL of XML file
    * @param int Length of time to cache
    * @return object XML_Tree object
    */
    function retrieveXML($url) {
        if ($data = $this->retrieveFile($url)) {

            $options = array(
                              XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => true,
                              XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false
                            );

            $unserializer = new XML_Unserializer($options);

            $status = $unserializer->unserialize($data, false);

            if (PEAR::isError($status)) {
                return $status;
            }

            return $unserializer->getUnserializedData();
        }

        return false;
    }


}


