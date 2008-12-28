<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/ExchangeRates.php';

require_once 'Services/ExchangeRates/Rates_NBP.php';

require_once 'Services/ExchangeRates/Transport/Mock.php';

class Services_ExchangeRates_RatesNBPTest extends PHPUnit_Framework_TestCase {

   public function testShouldRetrieveInformation() {
        // A much reduced html page!
        $country_rate_html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Tabela A kursów średnich walut obcych</title>
</head>
<body>
<table border="0" width="380">
<tr><td class="file"><br /><a href="xml/a251z081224.xml">powyższa tabela w formacie .xml</a><br /><br /></td></tr>
</table>
</body>
</html>';

$country_rate_xml = '<?xml version="1.0" encoding="ISO-8859-2"?>
<tabela_kursow typ="A" uid="08a251">
   <numer_tabeli>251/A/NBP/2008</numer_tabeli>
   <!-- Date published -->
   <data_publikacji>2008-12-24</data_publikacji>
   <pozycja>
      <nazwa_waluty>bat (Tajlandia)</nazwa_waluty>
      <przelicznik>1</przelicznik>
      <kod_waluty>THB</kod_waluty>

      <kurs_sredni>0,0848</kurs_sredni>
   </pozycja>
   <pozycja>
      <nazwa_waluty>dolar amerykański</nazwa_waluty>
      <przelicznik>1</przelicznik>
      <kod_waluty>USD</kod_waluty>
      <kurs_sredni>2,9313</kurs_sredni>

   </pozycja>
   <pozycja>
      <nazwa_waluty>dolar australijski</nazwa_waluty>
      <przelicznik>1</przelicznik>
      <kod_waluty>AUD</kod_waluty>
      <kurs_sredni>1,9981</kurs_sredni>
   </pozycja>

</tabela_kursow>';

        $rates = new Services_ExchangeRates();

        $rateProvider     = $rates->factory('Rates_NBP');


        $rateProvider->setTransport(new Services_ExchangeRates_Transport_Mock(array($country_rate_html, $country_rate_xml)));

        $data = $rateProvider->retrieve();

        //Compare strings here to avoid floating point SNAFU
        $this->assertSame("1", (string)$data['rates']["PLN"]);
        $this->assertSame("11.7924528302", (string)$data['rates']["THB"]);

        $this->assertSame("0.341145566813", (string)$data['rates']["USD"]);
        $this->assertSame("0.500475451679", (string)$data['rates']["AUD"]);

        $this->assertSame("2008-12-24", $data['date']);
        $this->assertSame("http://www.nbp.pl/Kursy/xml/a251z081224.xml", $data['source']);     
    }

    public function testShouldExtractDataFromNodes1() {
        $this->markTestIncomplete("Tests _extractNodeInformation");
    }

    public function testShouldExtractDataFromNodes2() {
        $this->markTestIncomplete("Tests _extractNodeInformation, with randomly shuffled nodes.");
    }
}
