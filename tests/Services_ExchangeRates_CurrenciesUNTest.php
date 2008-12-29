<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Currencies_UN.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRates_CurrenciesUNTest extends PHPUnit_Framework_TestCase {

    public function testShouldParseInformationCorrectly() {
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

        $rates = new Services_ExchangeRates();
        $currencyProvider = $rates->factory('Currencies_UN');

        $currencyProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_code_xml)));


        $data = $currencyProvider->retrieve();
        $currencies = array('AFA' => "Afghani", "ALL" => "Leck", "DZD" => "Algerian Dinar");

        $this->assertSame($currencies, $data);
    }
}
