<?php
class Services_ExchangeRates_Transport_Mock {
    var $responses;

    function Services_ExchangeRates_Transport_Mock($responses = array()) {
        $this->responses = $responses;
    }

    function fetch($url) {
        if (!empty($this->responses)) {
            return array_shift($this->responses);
        }

        return false;
    } 
}
