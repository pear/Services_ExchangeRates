<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRatesTest extends PHPUnit_Framework_TestCase {

    public function testShouldStoreRetrievedData() {
        $rates = new Services_ExchangeRates();

        $ratesProvider = $rates->factory('Rates');
        $ratesProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array('')));

        $currencyProvider = $rates->factory('Currencies');
        $currencyProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array('')));

        $rates->fetch($ratesProvider, $currencyProvider);

        $this->assertSame('1970-01-01', $rates->ratesUpdated);
        $this->assertSame(array(), $rates->rates);
        $this->assertSame(null, $rates->ratesSource);      

        $this->assertSame(array(), $rates->currencies);  
    }

    public function testShouldStoreRetrievedData2() {
        $country_code_xml = '<?xml version="1.0" encoding="UTF-8"?>
<CurrencyCodeList>
	<Currency>
		<CurrencyCoded>AFA</CurrencyCoded>
		<CurrencyName>Afghani</CurrencyName>
	</Currency>

	<Currency>
		<CurrencyCoded>ALL</CurrencyCoded>
		<CurrencyName>Leck</CurrencyName>
	</Currency>
	<Currency>
		<CurrencyCoded>DZD</CurrencyCoded>
		<CurrencyName>Algerian Dinar</CurrencyName>

	</Currency>
</CurrencyCodeList>';

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
        $currencyProvider = $rates->factory('Currencies_UN');

        $rateProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_rate_xml)));
        $currencyProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_code_xml)));


        $rates->fetch($rateProvider, $currencyProvider);

        //Based on simulated data and expected behaviour of Rates_ECB, Currencies_UN
        $this->assertSame("1.4005", $rates->rates["AFA"]);
        $this->assertSame("126.65", $rates->rates["ALL"]);
        $this->assertSame("1.9558", $rates->rates["BGN"]);

        $this->assertSame("2008-12-24", $rates->ratesUpdated);
        $this->assertSame("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml", $rates->ratesSource);     

        $currencies = array('AFA' => "Afghani", "ALL" => "Leck", "DZD" => "Algerian Dinar");
        $this->assertSame($currencies, $rates->currencies);

        $currencies = array('AFA' => "Afghani", "ALL" => "Leck");
        $this->assertSame($currencies, $rates->validCurrencies);
    }


    public function testShouldFilterCurrenciesWithUnknownExchangeRates() {
        $mock_currencies = array('USD' => 'US Dollar', 'AUD' => 'Australian Dollar');
        $mock_rates      = array('AUD' => 1.00);

        $rates = new Services_ExchangeRates();

        $this->assertSame(array('AUD' => 'Australian Dollar'),
                          $rates->getValidCurrencies($mock_currencies, $mock_rates));
    }
}