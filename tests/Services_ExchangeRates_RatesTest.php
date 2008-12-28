<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Rates.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRates_RatesTest extends PHPUnit_Framework_TestCase {

   public function testShouldRetrieveInformation() {
        $transport = new Services_ExchangeRates_Transport_Mock(array(""));

        $provider = new Services_ExchangeRates_Rates($transport);

        $data = $provider->retrieve();
        $this->assertSame(array('source' => null, 'rates' => array(), 'date' => '1970-01-01'), $data);
    }
}
