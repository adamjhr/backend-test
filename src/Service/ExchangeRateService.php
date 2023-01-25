<?php
namespace Opeepl\BackendTest\Service;

/**
 * Main entrypoint for this library.
 */
class ExchangeRateService {

    private array $apiList;
    private array $supportedCurrencies;

    public function __construct() {
        $this->apiList = array (
            // I ran out of free API calls
            //new ApiLayerExchangeRateApi("ApiLayerExchangeRateApi", ""), // <--- this is the API i was asked to use
            new AlternativeExchangeRateApi("AlternativeExchangeRateApi - Fiat", ""),
            //new CoinApi("CoinApi", ""),
            new CoinGateApi("CoinGateApi - Crypto", "")
        );

        // Merge all lists of supported currencies
        $this->supportedCurrencies = array_merge(...array_column($this->apiList, 'supportedCurrencies'));
    }

    /**
     * Return all supported currencies
     *
     * @return array<string>
     */
    public function getSupportedCurrencies(): array {
        return $this->supportedCurrencies;

    }

    /**
     * Given the $amount in $fromCurrency, it returns the corresponding amount in $toCurrency.
     *
     * @param int $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return int
     */
    public function getExchangeAmount(int $amount, string $fromCurrency, string $toCurrency): int {

        $fromCurrencyApi = null;
        $toCurrencyApi = null;

        foreach ($this->apiList as $api) {
            // Search API's to find ones that support both currencies
            $searchFromCurrency = array_search($fromCurrency, $api->supportedCurrencies);
            $searchToCurrency = array_search($toCurrency, $api->supportedCurrencies);

            if ($searchFromCurrency !== false and $searchToCurrency !== false) {
                $result = $api->convertCurrency($amount, $fromCurrency, $toCurrency);
                return $result;
            } else if ($searchFromCurrency !== false and is_null($fromCurrencyApi)) {
                $fromCurrencyApi = $api;
            } else if ($searchToCurrency !== false and is_null($toCurrencyApi)) {
                $toCurrencyApi = $api;
            }
        }

        if (is_null($toCurrencyApi) or is_null($fromCurrencyApi)) {
            throw new \Exception("Encountered an unknown currency");
        }

        // Find a common currency between api's
        $overlap = array_intersect($fromCurrencyApi->supportedCurrencies, $toCurrencyApi->supportedCurrencies);

        // The last currenct of the list of common currencies is arbitrarily chosen as an intermediate currency
        $intermediateCurrency = array_pop($overlap);
        $firstConversionAmount = $fromCurrencyApi->convertCurrency($amount, $fromCurrency, $intermediateCurrency);
        $secondConversionAmount = $toCurrencyApi->convertCurrency($firstConversionAmount, $intermediateCurrency, $toCurrency);
        
        return $secondConversionAmount;
    }
}
