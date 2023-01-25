<?php
namespace Opeepl\BackendTest\Service;

use PHPUnit\Framework\TestCase;

class ExchangeRateServiceTest extends TestCase {

    protected $exchangeRateService;

    public function setUp(): void {
        $this->exchangeRateService = new ExchangeRateService();
    }

    /**
     * @test
     */
    public function getSupportedCurrenciesTest() {
        $currencies = $this->exchangeRateService->getSupportedCurrencies();

        $this->assertContains('USD', $currencies, 'Expected USD to be a supported currency');
        $this->assertContains('EUR', $currencies, 'Expected EUR to be a supported currency');
        $this->assertContains('DKK', $currencies, 'Expected DKK to be a supported currency');
        $this->assertContains('CAD', $currencies, 'Expected CAD to be a supported currency');
        $this->assertContains('BTC', $currencies, 'Expected BTC to be a supported currency');
        $this->assertContains('LTC', $currencies, 'Expected LTC to be a supported currency');
        $this->assertContains('ETH', $currencies, 'Expected ETH to be a supported currency');
        $this->assertContains('DOGE', $currencies, 'Expected ETH to be a supported currency');
    }

    /**
     * @test
     */
    public function getExchangeAmountEURToDKKTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(100, 'EUR', 'DKK');

        // Because of the fixed-rate policy between DKK and EUR, we should be able to expect 1 EUR to be between 7.4 and 7.6.
        $this->assertTrue(740 < $amount && $amount < 760);
    }

    /**
     * @test
     */
    public function getExchangeAmountUSDToCADTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(200, 'USD', 'CAD');

        // For the sake of simplicity, we expect USD to CAD to be between 1.2 and 1.45.
        $this->assertTrue(240 < $amount && $amount < 290);
    }

    /**
     * @test
     */
    public function getExchangeAmountUSDToUSDTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(200, 'USD', 'USD');

        $this->assertEquals(200, $amount);
    }

    /**
     * @test
     */
    public function getExchangeAmountETHToBTCTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(1000, 'ETH', 'BTC');

        $this->assertTrue(60 < $amount && $amount < 80);
    }

    /**
     * @test
     */
    public function getExchangeAmountBTCToUSDTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(1, 'BTC', 'USD');

        $this->assertTrue(15000 < $amount && $amount < 27000);
    }

    /**
     * @test
     */
    public function getExchangeAmountDKKToETHTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(1000000, 'DKK', 'ETH');

        $this->assertTrue(85 < $amount && $amount < 105);
    }

    /**
     * @test
     */
    public function getExchangeAmountDOGEToEURTest() {
        $amount = $this->exchangeRateService->getExchangeAmount(1000, 'DOGE', 'EUR');

        $this->assertTrue(55 < $amount && $amount < 90);
    }
}
