<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';
require_once 'Services/ExchangeRates/Common.php';
require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRatesCommonTest extends PHPUnit_Framework_TestCase {

    public function testShouldUseTransportToGetFile() {
        $transport = new Services_ExchangeRates_Transport_Mock(array("Hello", "World"));

        $provider = new Services_ExchangeRates_Common($transport);

        $this->assertSame("Hello", $provider->retrieveFile('http://example.com/'));
        $this->assertSame("World", $provider->retrieveFile('http://example.com/'));
    }

   public function testShouldParseXML() {
        $transport = new Services_ExchangeRates_Transport_Mock(array("<b>Hello</b>", "<b>World</b>"));

        $provider = new Services_ExchangeRates_Common($transport);

        $data = $provider->retrieveXML('http://example.com/');
        $this->assertSame("Hello", $data);

        $data = $provider->retrieveXML('http://example.com/');
        $this->assertSame("World", $data);
    }

}
