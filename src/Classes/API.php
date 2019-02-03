<?php namespace Mare06xa\Beez\Classes;

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

    protected function formatParameters($data)
    {
        if (is_string($data)) return $data;

        return $data->toString();
    }

    public function call($resource = "", $method = "", $data = "", $format = null, $explore = false)
    {
        $resultData = "";
        $postData   = $this->formatParameters($data);
        $headerStr  = "POST /API?&_r={" . $resource . "}&_m={" . $method . "}&_f={" . $format . "}" . ($explore?"&_x=1":"")." HTTP/1.1\r\n";

        $postHeaders = [
            "Host"           => $this->apiDomain,
            "Content-Type"   => "application/x-www-form-urlencoded",
            "User-Agent"     => "PHP-strpc-client",
            "Content-Length" => strlen($postData),
            "Authorization"  => "Basic " . base64_encode($this->apiToken . ':x'),
            "Connection"     => "close\r\n"
        ];

        foreach ($postHeaders as $postHeader => $headerValue) {
            $headerStr .= $postHeader . ": " . $headerValue . "\r\n";
        }

        $socketConn = fsockopen(
            "ssl://{$this->apiDomain}",
            "443",
            $errNo,
            $errStr,
            30);

        try {
            fputs($socketConn, $headerStr . $postData);

            while (!feof($socketConn))
                $resultData .= fgets ($socketConn, 128);

            fclose ($socketConn);
        } catch (\Exception $exception) {
            return $exception->getTrace();
        }

        $resultData = trim(substr($resultData, strpos($resultData, "\r\n\r\n") + 4));
        $resultData = str_replace("'", '"', $resultData);
        $resultData = json_decode($resultData, true);

        return new Result($resultData);
    }

    public function getBinaryPDF($invoiceID, $documentTitle, $language)
    {
        $headerStr = [
            'http' => [
                'method' => "GET",
                'header' => "Authorization: Basic " . base64_encode($this->apiToken . ':x') . "\r\n"
            ]
        ];

        $context = stream_context_create($headerStr);

        return file_get_contents(
            "https://{" . $this->apiDomain . "}/API-pdf?id=" . $invoiceID . "&res=invoice-sent&format=PDF&doctitle=" . $documentTitle . "&lang=" . $language,
            false,
            $context);
    }
}