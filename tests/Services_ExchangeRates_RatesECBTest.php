<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Rates_ECB.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRates_RatesECBTest extends PHPUnit_Framework_TestCase {

   public function testShouldRetrieveInformation() {
        $country_rate_xml = '<?xml version="1.0" encoding="UTF-8"?>
        <gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
	        <gesmes:subject>Reference rates</gesmes:subject>
	        <gesmes:Sender>
		        <gesmes:name>European Central Bank</gesmes:name>
	        </gesmes:Sender>
	        <Cube>
		        <Cube time="2008-12-24">
			        <Cube currency="AFA" rate="1.4005"/>
			        <Cube currency="ALL" rate="126.65"/>
			        <Cube currency="BGN" rate="1.9558"/>
		        </Cube>
	        </Cube>
        </gesmes:Envelope>';

        $rates = new Services_ExchangeRates();

        $rateProvider     = $rates->factory('Rates_ECB');


        $rateProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_rate_xml)));

        $data = $rateProvider->retrieve();

        //Based on simulated data and expected behaviour of Rates_ECB, Currencies_UN
        $this->assertSame("1.4005", $data['rates']["AFA"]);
        $this->assertSame("126.65", $data['rates']["ALL"]);
        $this->assertSame("1.9558", $data['rates']["BGN"]);

        $this->assertSame("2008-12-24", $data['date']);
        $this->assertSame("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml", $data['source']);     
    }
}
