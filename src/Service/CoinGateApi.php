<?php
namespace Opeepl\BackendTest\Service;

class CoinGateApi extends ExchangeRateApi {

    function __construct($name, $apiKey) {
        parent::__construct($name, $apiKey);
    }

    protected function getSupportedCurrencies(): array {
        /* $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.coingate.com/api/v2/currencies",
            CURLOPT_HTTPHEADER => array(
              "accept: application/json"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        
        $response = curl_exec($curl);

        if(curl_error($curl)) {
            throw new \Exception(curl_error($curl));
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        return array_column($decoded, 'symbol') ?? array();  */

        // This is to make sure the service behaves properly when converting between currencies
        // that are not available on the same API
        return array("BTC", "USD", "LTC", "ETH", "DOGE");
    }

    public function convertCurrency(int $amount, string $fromCurrency, string $toCurrency): int {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.coingate.com/api/v2/rates/merchant/{$fromCurrency}/{$toCurrency}",
            CURLOPT_HTTPHEADER => array(
              "accept: text/plain",
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        
        $response = curl_exec($curl);

        if(curl_error($curl)) {
            throw new \Exception(curl_error($curl));
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        return $decoded * $amount; 
    }

}