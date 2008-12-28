<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Rates_NBI.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRates_RatesNBITest extends PHPUnit_Framework_TestCase {

   public function testShouldRetrieveInformation() {
        $country_rate_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<CURRENCIES>
<LAST_UPDATE>2008-12-24</LAST_UPDATE>
<CURRENCY>
<NAME>Dollar</NAME>
<UNIT>1</UNIT>
<CURRENCYCODE>USD</CURRENCYCODE>
<COUNTRY>USA</COUNTRY>
<RATE>3.873</RATE>
<CHANGE>0.991</CHANGE>

</CURRENCY>

<CURRENCY>
<NAME>Pound</NAME>
<UNIT>1</UNIT>
<CURRENCYCODE>EGP</CURRENCYCODE>

<COUNTRY>Egypt</COUNTRY>
<RATE>0.701</RATE>
<CHANGE>0.907</CHANGE>
</CURRENCY>
</CURRENCIES>';

        $rates = new Services_ExchangeRates();

        $rateProvider = $rates->factory('Rates_NBI');


        $rateProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_rate_xml)));

        $data = $rateProvider->retrieve();

        //Cast to string to avoid floating point snafu
        $this->assertSame("1", (string)$data['rates']["ILS"]);
        $this->assertSame("0.258197779499", (string)$data['rates']["USD"]);
        $this->assertSame("1.42653352354", (string)$data['rates']["EGP"]);

        $this->assertSame("2008-12-24", $data['date']);
        $this->assertSame("http://www.bankisrael.gov.il/heb.shearim/currency.php", $data['source']);     
    }
}
