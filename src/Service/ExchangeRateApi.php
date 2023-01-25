<?php
namespace Opeepl\BackendTest\Service;

abstract class ExchangeRateApi {
    public array $supportedCurrencies;
    public string $name;
    protected string $apiKey;
    public function __construct(string $name, string $apiKey) {
        $this->name = $name;
        $this->apiKey = $apiKey;
        $this->supportedCurrencies = $this->getSupportedCurrencies();
    }
    abstract protected function getSupportedCurrencies(): array;
    abstract public function convertCurrency(int $amount, string $fromCurrency, string $toCurrency): int;
}