<?php
class Services_ExchangeRates_Transport_Mock
{
    var $responses;

    public function __construct($responses = array()) 
    {
        $this->responses = $responses;
    }

    public function fetch($url) 
    {
        if (!empty($this->responses)) {
            return array_shift($this->responses);
        }

        return false;
    } 
}
