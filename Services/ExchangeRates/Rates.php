<?php
require_once 'Services/ExchangeRates/Common.php';

class Services_ExchangeRates_Rates extends Services_ExchangeRates_Common {
    function retrieve() {
        return array('source' => null, 'rates' => array(), 'date' => date("Y-m-d", 0));
    }
}
