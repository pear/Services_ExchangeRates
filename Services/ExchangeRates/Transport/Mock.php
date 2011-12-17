<?php
/**
 * PHP Version 5
 *
 * @package   Services_ExchangeRates
 * @author    Marshall Roch <marshall@exclupen.com>
 * @author    Colin Ross <cross@php.net>
 * @author    Daniel O'Connor <daniel.oconnor@gmail.com>
 * @copyright 2003 Daniel O'Connor
 * @license   http://www.php.net/license/2_02.txt PHP License 2.0
 */


/**
 * A mock transport
 *
 * @package   Services_ExchangeRates
 * @author    Marshall Roch <marshall@exclupen.com>
 * @author    Colin Ross <cross@php.net>
 * @author    Daniel O'Connor <daniel.oconnor@gmail.com>
 * @copyright Copyright 2003 Marshall Roch
 * @license   http://www.php.net/license/2_02.txt PHP License 2.0
 */
class Services_ExchangeRates_Transport_Mock
{
    var $responses;

    /**
     * Constructor.
     *
     * @param array $responses A sequential list of responses to be returned.
     */
    public function __construct($responses = array()) 
    {
        $this->responses = $responses;
    }

    /**
     * Fetch the URL
     *
     * @param string $url URL to fetch
     *
     * @return mixed
     */
    public function fetch($url) 
    {
        if (!empty($this->responses)) {
            return array_shift($this->responses);
        }

        return false;
    } 
}
