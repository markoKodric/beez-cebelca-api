<?php namespace Mare06xa\Beez;

use Mare06xa\Beez\Classes\API;

class Beez
{
    protected $API;
    protected $result;


    public function __construct()
    {
        $this->API    = new API();
        $this->result = [];
    }

    protected function invoiceID()
    {
        return $this->result['invoiceID'][0][0]['id'];
    }

    public function getResult($key = null)
    {
        if ($key) return $this->result[$key];

        return $this->result;
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
        $apiResponse = $this->API->post(
            'partner',
            'assure',
            $data);

        if ($apiResponse['statusCode'] !== 200)
            throw new \Exception($apiResponse['responseBody']);

        $this->result['customerID'] = $apiResponse['responseBody'];

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
        if (!array_key_exists('customerID', $this->result)) {
            throw new \Exception('Customer ID from previous api call (insertCustomer) is required.');
        }

        $data['id_partner'] = $this->result['customerID'][0][0]['id'];

        if ($withMoreOptions) {
            $apiResponse = $this->API->post(
                'invoice-sent',
                'insert-smart-2',
                $data);
        } else {
            $apiResponse = $this->API->post(
                'invoice-sent',
                'insert-into',
                $data);
        }

        if ($apiResponse['statusCode'] !== 200)
            throw new \Exception($apiResponse['responseBody']);

        $this->result['invoiceID'] = $apiResponse['responseBody'];

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
        if (!array_key_exists('invoiceID', $this->result)) {
            throw new \Exception('Invoice ID from previous api call (insertHead) is required.');
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['id_invoice_sent'] = $this->invoiceID();

            $apiResponse = $this->API->post(
                'invoice-sent-b',
                'insert-into',
                $data[$i]);

            if ($apiResponse['statusCode'] !== 200)
                throw new \Exception($apiResponse['responseBody']);

            $this->result['responseInsertItems'] = $apiResponse['responseBody'];
        }

        return $this;
    }

    /**
     * Insert payment data into the invoice
     *
     * @param array $data
     * @param bool $withoutAmount
     * @return $this
     * @throws \Exception
     */
    public function insertPayment($data = [], $withoutAmount = false)
    {
        if (!array_key_exists('invoiceID', $this->result)) {
            throw new \Exception('Invoice ID from previous api call (insertHead or insertItems) is required.');
        }

        $data['id_invoice_sent'] = $this->invoiceID();

        if ($withoutAmount) {
            $apiResponse = $this->API->post(
                'invoice-sent-p',
                'mark-paid',
                $data);
        } else {
            $apiResponse = $this->API->post(
                'invoice-sent-p',
                'insert-into',
                $data);
        }

        if ($apiResponse['statusCode'] !== 200)
            throw new \Exception($apiResponse['responseBody']);

        $this->result['responseInsertPayment'] = $apiResponse['responseBody'];

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
        if (!array_key_exists('invoiceID', $this->result)) {
            throw new \Exception('Invoice ID from previous api call (insertHead | insertItems | insertPayment) is required.');
        }

        $data['id'] = $this->invoiceID();

        if ($noLocation) {
            $apiResponse = $this->result['fiscalizeData'] = $this->API->post(
                'invoice-sent',
                'finalize-invoice-2015',
                $data);
        } else {
            $apiResponse = $this->API->post(
                'invoice-sent',
                'finalize-invoice',
                $data);
        }

        if ($apiResponse['statusCode'] !== 200)
            throw new \Exception($apiResponse['responseBody']);

        $this->result['fiscalizeData'] = $apiResponse['responseBody'];

        $this->result['fiscalInfo'] = $this->API->post(
            'invoice-sent',
            'get-fiscal-info',
            [
                'id' => $data['id']
            ]);

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
        $this->result['invoicePDF'] = $this->API->getPDF(
            $this->invoiceID(),
            $pdfTitle,
            $language);

        $pdfPath = $storagePath . $pdfTitle . '.pdf';

        file_put_contents($pdfPath, $this->result['invoicePDF']);

        return $this;
    }

    /**
     * @param array $data
     * @param bool $testMode
     * @return $this
     */
    public function addLocationFURS($data = [], $testMode = false)
    {
        $this->result['fursLocation'] = $this->API->post(
            'sales-location',
            'insert-into',
            $data)['responseBody'];

        $this->API->post(
            'sales-location',
            'register-at-furs',
            [
                'id' =>  $this->result['fursLocation'][0][0]['id'],
                'test_mode' => $testMode ? "1" : "0"
            ]);

        return $this;
    }
}