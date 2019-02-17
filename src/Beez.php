<?php namespace Mare06xa\Beez;

use Mare06xa\Beez\Classes\API;
use Mare06xa\Beez\Classes\Currency;
use Mare06xa\Beez\Validations\CustomerValidation;
use Mare06xa\Beez\Validations\InvoiceHeadValidation;
use Mare06xa\Beez\Validations\InvoiceItemValidation;
use Mare06xa\Beez\Validations\InvoiceValidation;
use Mare06xa\Beez\Validations\PaymentValidation;
use Mare06xa\Beez\Validations\PreviousCallValidation;

class Beez
{
    protected $API;
    protected $result;
    protected $testMode;
    protected $currency;
    protected $conversionRate;

    public function __construct()
    {
        $this->API            = new API();
        $this->result         = [];
        $this->testMode       = $this->API->testMode();
        $this->currency       = Currency::EUR;
        $this->conversionRate = 1;
    }

    public function invoiceID()
    {
        return $this->result['invoiceID'][0][0]['id'];
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null)
    {
        if ($key) return $this->result[$key];

        return $this->result;
    }

    public function getLocation()
    {
        return $this->result['locationData'][0][0]['id'];
    }

    public function getCustomerID()
    {
        return $this->result['customerID'][0][0]['id'];
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function setResult($key, $value)
    {
        $this->result[$key] = $value;
    }

    /**
     * @param array $data
     * @param string $key
     * @param mixed $value
     * @return array
     */
    protected function setData(&$data, $key, $value)
    {
        return $data[$key] = $value;
    }

    public function setCurrency($currencyID)
    {
        $this->currency = $currencyID;
        $this->conversionRate = Currency::getConversionRate($currencyID);
    }

    /**
     * @param InvoiceValidation $invoiceValidation
     * @throws \Exception
     */
    protected function validateData(InvoiceValidation $invoiceValidation)
    {
        if ($invoiceValidation->fails())
            throw new \Exception($invoiceValidation->getMessage());
    }

    /**
     * @param $apiResponse
     * @throws \Exception
     */
    protected function validateResponseStatus($apiResponse)
    {
        if ($apiResponse['statusCode'] !== 200)
            throw new \Exception(json_encode($apiResponse['responseBody']));
    }

    /**
     * @param PreviousCallValidation $validation
     * @throws \Exception
     */
    protected function validatePreviousCall(PreviousCallValidation $validation)
    {
        if ($validation->fails())
            throw new \Exception($validation->getMessage());
    }

    /**
     * Inserts the customer in DB and returns it's ID.
     * If customer already exists in DB it only returns it's ID.
     *
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function insertCustomer($data = [])
    {
        $this->validateData(new CustomerValidation($data));

        $apiResponse = $this->API->post('partner', 'assure', $data);

        $this->validateResponseStatus($apiResponse);
        $this->setResult('customerID', $apiResponse['responseBody']);

        return $this;
    }

    /**
     * Insert head data into the invoice and returns it's ID.
     *
     * @param array $data
     * @param bool $withMoreOptions
     * @return $this
     * @throws \Exception
     */
    public function insertHead($data = [], $withMoreOptions = false)
    {
        $this->validateData(new InvoiceHeadValidation($data));
        $this->validatePreviousCall(new PreviousCallValidation([
            'customerID' => $this->result,
            'taxnum'     => $data
        ], 'insertCustomer'));

        if (!array_key_exists('taxnum', $data))
            $this->setData($data, 'id_partner', $this->getCustomerID());
        else
            $this->setData($data, 'id_partner', 0);

        if ($this->getCurrency() != Currency::EUR) {
            $this->setData($data, 'id_currency', $this->getCurrency());
            $this->setData($data, 'conv_rate',   $this->getConversionRate());
        }

        if ($withMoreOptions)
            $apiResponse = $this->API->post('invoice-sent', 'insert-smart-2', $data);
        else
            $apiResponse = $this->API->post('invoice-sent', 'insert-into', $data);

        $this->validateResponseStatus($apiResponse);
        $this->setResult('invoiceID', $apiResponse['responseBody']);

        return $this;
    }

    /**
     * Insert body data (products, services,...) into the invoice
     *
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function insertItems($data = [])
    {
        $this->validatePreviousCall(new PreviousCallValidation(['invoiceID' => $this->result], 'insertHead'));

        foreach ($data as $item) {
            $this->validateData(new InvoiceItemValidation($item));
            $this->setData($item, 'id_invoice_sent', $this->invoiceID());

            $apiResponse = $this->API->post('invoice-sent-b', 'insert-into', $item);

            $this->validateResponseStatus($apiResponse);
            $this->setResult('responseInsertItems', $apiResponse['responseBody']);
        }

        return $this;
    }

    /**
     * Insert payment data into the invoice
     *
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function insertPayment($data = [])
    {
        $this->validatePreviousCall(new PreviousCallValidation(['invoiceID' => $this->result], 'insertItems'));
        $this->validateData(new PaymentValidation($data));
        $this->setData($data, 'id_invoice_sent', $this->invoiceID());

        if (!array_key_exists('amount', $data))
            $apiResponse = $this->API->post('invoice-sent-p', 'mark-paid', $data);
        else
            $apiResponse = $this->API->post('invoice-sent-p', 'insert-into', $data);

        $this->validateResponseStatus($apiResponse);
        $this->setResult('responseInsertPayment', $apiResponse['responseBody']);

        return $this;
    }

    /**
     * @param array $data
     * @param bool $noLocation
     * @return $this
     * @throws \Exception
     */
    public function fiscalizeInvoice($data = [], $noLocation = false)
    {
        $this->validatePreviousCall(new PreviousCallValidation(['invoiceID' => $this->result], 'insertPayment'));
        $this->setData($data, "id", $this->invoiceID());

        if ($this->testMode)
            $this->setData($data, "test_mode", 1);

        if ($noLocation)
            $apiResponse = $this->API->post('invoice-sent', 'finalize-invoice-2015', $data);
        else
            $apiResponse = $this->API->post('invoice-sent', 'finalize-invoice', $data);

        $this->validateResponseStatus($apiResponse);
        $this->setResult('fiscalizeData', $apiResponse['responseBody']);

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function getFiscalInfo($data = [])
    {
        if ($this->testMode)
            throw new \Exception("You cannot use function getFiscalInfo in test mode");

        if (!array_key_exists('invoiceID', $data) && empty($data) && $this->invoiceID())
            $this->setData($data, 'id', $this->invoiceID());

        $apiResponse = $this->API->post('invoice-sent', 'get-fiscal-info', $data);

        $this->validateResponseStatus($apiResponse);
        $this->setResult('fiscalInfo', $apiResponse['responseBody']);

        return $this;
    }

    /**
     * @param $storagePath
     * @param $pdfTitle
     * @param string $language
     * @return $this
     */
    public function generatePDF($storagePath, $pdfTitle, $language = "si")
    {
        $this->result['invoicePDF'] = $this->API->getPDF($this->invoiceID(), $pdfTitle, $language);

        $pdfPath = $storagePath . $pdfTitle . '.pdf';

        file_put_contents($pdfPath, $this->result['invoicePDF']);

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function addLocation($data = [])
    {
        $this->result['locationData'] = $this->API->post('sales-location', 'insert-into', $data)['responseBody'];

        $this->API->post('sales-location', 'register-at-furs', [
            'id'        => $this->getLocation(),
            'test_mode' => $this->testMode ? "1" : "0"
        ]);

        return $this;
    }
}