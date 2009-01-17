<?php
class Services_ExchangeRates_Transport_Default {
    public function fetch($url) {
        return file_get_contents($url);
    } 
}
