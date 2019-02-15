<?php namespace Mare06xa\Beez\Classes;

use GuzzleHttp\Client;

class API
{
    protected $apiToken;
    protected $apiDomain;
    protected $debugMode;

    public function __construct()
    {
        $this->apiToken  = env("BIZ_TOKEN");
        $this->apiDomain = env("BIZ_DOMAIN");
        $this->debugMode = env("BIZ_DEBUG");
    }

    public function post($resource = "", $method = "", $data = "", $format = null, $explore = false)
    {
        $apiClient = new Client();

        $apiResponse = $apiClient->post($this->apiDomain . '/API', [
            'query' => [
                '_r' => $resource,
                '_m' => $method
            ],
            'auth' => [$this->apiToken, 'x'],
            'headers' => [
                "User-Agent"     => "PHP-strpc-client",
                "Content-Type"   => "application/x-www-form-urlencoded",
                "Connection"     => "close",
            ],
            'form_params' => $data,
            'debug' => fopen("php://stderr", "w+"),
            'allow_redirects' => [
                'strict' => true,
                'referer' => true
            ],
            'synchronous' => true
        ]);

        return [
            'responseBody' => json_decode($apiResponse->getBody()->getContents(), true),
            'statusCode'   => $apiResponse->getStatusCode()
        ];
    }

    public function getPDF($invoiceID, $pdfTitle, $language)
    {
        $apiClient = new Client();

        $apiResponse = $apiClient->request('GET', $this->apiDomain . '/API-pdf', [
            'auth' => [$this->apiToken, 'x'],
            'query' => [
                'id' => $invoiceID,
                'res' => 'invoice-sent',
                'format' => 'PDF',
                'doctitle' => $pdfTitle,
                'lang' => $language
            ],
            'stream' => true,
            'debug' => fopen("php://stderr", "w+")
        ]);

        return $apiResponse->getBody()->getContents();
    }
}