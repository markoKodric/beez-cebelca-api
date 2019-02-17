<?php

namespace Mare06xa\Beez\Classes;


use GuzzleHttp\Client;

class Currency
{
    /*
     * Currency constants
     */
    const EUR = 1;
    const USD = 2;
    const GBP = 3;
    const HRK = 4;
    const PLN = 5;
    const XAU = 6;
    const HUF = 7;
    const CZK = 8;

    protected static $currencyIDs = [
        1 => "EUR",
        2 => "USD",
        3 => "GBP",
        4 => "HRK",
        5 => "PLN",
        6 => "XAU",
        7 => "HUF",
        8 => "CZK"
    ];

    public static function getConversionRate($toCurrency, $fromCurrency = self::EUR)
    {
        $apiClient = new Client();

        $apiResponse = $apiClient->request('GET', 'https://api.exchangeratesapi.io/latest', [
            'query' => [
                'base'    => self::$currencyIDs[$fromCurrency],
                'symbols' => self::$currencyIDs[$toCurrency]
            ]
        ])->getBody()->getContents();

        return json_decode($apiResponse, true)['rates'][self::$currencyIDs[$toCurrency]];
    }
}