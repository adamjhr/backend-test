<?php
namespace Opeepl\BackendTest\Service;

class AlternativeExchangeRateApi extends ExchangeRateApi {

    function __construct($name, $apiKey) {
        parent::__construct($name, $apiKey);
    }

    protected function getSupportedCurrencies(): array {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://v6.exchangerate-api.com/v6/{$this->apiKey}/codes",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: text/plain",
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

        return array_column($decoded["supported_codes"], 0) ?? array();
    }

    public function convertCurrency(int $amount, string $fromCurrency, string $toCurrency): int {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://v6.exchangerate-api.com/v6/{$this->apiKey}/pair/{$fromCurrency}/{$toCurrency}/{$amount}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
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
        
        return $decoded["conversion_result"]; 
    }

}