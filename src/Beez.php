<?php namespace Mare06xa\Beez;

use Mare06xa\Beez\Classes\API;
use Mare06xa\Beez\Classes\Customer;
use Mare06xa\Beez\Classes\InvoiceHead;
use Mare06xa\Beez\Classes\InvoiceItems;
use Mare06xa\Beez\Classes\Location;
use Mare06xa\Beez\Classes\Payments;
use Mare06xa\Beez\Classes\FiscalizeData;

class Beez
{
    protected $API;
    protected $result;


    public function __construct()
    {
        $this->API    = new API();
        $this->result = [];
    }

    /**
     * Inserts the customer in DB and returns it's ID.
     * If customer already exists in DB it only returns it's ID.
     *
     * @param Customer $customer
     * @return $this
     */
    public function insertCustomer(Customer $customer)
    {
        $this->result = $this->API->call(
            'partner',
            'assure',
            $customer);

        return $this;
    }

    /**
     * Insert head data into the invoice and returns it's ID.
     *
     * @param InvoiceHead $invoiceHead
     * @param bool $withMoreOptions
     * @return $this
     */
    public function insertHead(InvoiceHead $invoiceHead, $withMoreOptions = false)
    {
        if ($withMoreOptions) {
            $this->result = $this->API->call(
                'invoice-sent',
                'insert-smart-2',
                $invoiceHead);
        } else {
            $this->result = $this->API->call(
                'invoice-sent',
                'insert-into',
                $invoiceHead);
        }

        return $this;
    }

    /**
     * Insert body data (products, services,...) into the invoice
     *
     * @param InvoiceItems $invoiceItems
     * @return $this
     */
    public function insertItems(InvoiceItems $invoiceItems)
    {
        $this->result = $this->API->call(
            'invoice-sent-b',
            'insert-into',
            $invoiceItems->toJSON());

        return $this;
    }

    /**
     * Insert payment data into the invoice
     *
     * @param Payments $payments
     * @param bool $withoutAmount
     * @return $this
     */
    public function insertPayment(Payments $payments, $withoutAmount = false)
    {
        if ($withoutAmount) {
            $this->result = $this->API->call(
                'invoice-sent-p',
                'mark-paid',
                $payments->toJSON());
        } else {
            $this->result = $this->API->call(
                'invoice-sent-p',
                'insert-into',
                $payments->toJSON());
        }

        return $this;
    }

    /**
     * @param FiscalizeData $fiscalizeData
     * @param bool $noLocation
     * @return $this
     */
    public function fiscalizeInvoice(FiscalizeData $fiscalizeData, $noLocation = false)
    {
        if ($noLocation) {
            $this->result = $this->API->call(
                'invoice-sent',
                'finalize-invoice-2015',
                $fiscalizeData);
        } else {
            $this->result = $this->API->call(
                'invoice-sent',
                'finalize-invoice',
                $fiscalizeData);
        }

        return $this;
    }

    /**
     * @param $invoiceID
     * @return $this
     */
    public function getFiscalInfo($invoiceID)
    {
        $this->result = $this->API->call(
            'invoice-sent',
            'get-fiscal-info',
            $invoiceID);

        return $this;
    }

    /**
     * @param $invoiceID
     * @param $storagePath
     * @param $documentTitle
     * @param string $language
     * @return $this
     */
    public function generatePDF($invoiceID, $storagePath, $documentTitle, $language = "si")
    {
        $this->result = $this->API->getBinaryPDF($invoiceID, $documentTitle, $language);

        $pdfPath = $storagePath . "/" . $documentTitle . ".pdf";
        file_put_contents($pdfPath, $this->result);

        return $this;
    }

    /**
     * @param Location $location
     * @param bool $testMode
     * @return $this
     */
    public function addLocationFURS(Location $location, $testMode = false)
    {
        $this->result = $this->API->call(
            'sales-location',
            'insert-into',
            $location);

        $this->API->call(
            'sales-location',
            'register-at-furs',
            "id=" . $this->result . "&test_mode=" . $testMode . "\r\n");

        return $this;
    }
}