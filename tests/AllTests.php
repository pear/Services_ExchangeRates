<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Services_ExchangeRates_AllTests::main');
}

// PHPUnit inlcudes
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';


require_once 'Services_ExchangeRatesTest.php';
require_once 'Services_ExchangeRatesCommonTest.php';
require_once 'Services_ExchangeRates_RatesTest.php';
require_once 'Services_ExchangeRates_CurrenciesTest.php';

require_once 'Services_ExchangeRates_CurrenciesUNTest.php';

require_once 'Services_ExchangeRates_RatesECBTest.php';
require_once 'Services_ExchangeRates_RatesNBPTest.php';
require_once 'Services_ExchangeRates_RatesNBITest.php';

class Services_ExchangeRates_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services_ExchangeRates Tests');
        $suite->addTestSuite('Services_ExchangeRatesTest');
        $suite->addTestSuite('Services_ExchangeRatesCommonTest'); //I should rename!

        $suite->addTestSuite('Services_ExchangeRates_RatesTest');
        $suite->addTestSuite('Services_ExchangeRates_CurrenciesTest');

        $suite->addTestSuite('Services_ExchangeRates_CurrenciesUNTest');

        $suite->addTestSuite('Services_ExchangeRates_RatesECBTest');
        $suite->addTestSuite('Services_ExchangeRates_RatesNBPTest');
        $suite->addTestSuite('Services_ExchangeRates_RatesNBITest');
        return $suite;
    }
}


// exec test suite
if (PHPUnit_MAIN_METHOD == 'Services_ExchangeRates_AllTests::main') {
    Services_ExchangeRates_AllTests::main();
}
?>
