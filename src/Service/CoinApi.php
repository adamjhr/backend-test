<?php
namespace Opeepl\BackendTest\Service;

class CoinApi extends ExchangeRateApi {

    function __construct($name, $apiKey) {
        parent::__construct($name, $apiKey);
    }

    protected function getSupportedCurrencies(): array {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://rest.coinapi.io/v1/assets",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: text/plain",
              "X-CoinAPI-Key: {$this->apiKey}"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
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

        return array_column($decoded, 'asset_id'); 

    }

    public function convertCurrency(int $amount, string $fromCurrency, string $toCurrency): int {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://rest.coinapi.io/v1/{$fromCurrency}/{$toCurrency}",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: text/plain",
              "X-CoinAPI-Key: {$this->apiKey}"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
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

        return $decoded["rate"] * $amount; 
    }

}