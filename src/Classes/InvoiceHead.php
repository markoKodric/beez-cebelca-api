<?php

namespace Mare06xa\Beez\Classes;


class InvoiceHead
{
    protected $dateSent;
    protected $dateToPay;
    protected $dateServed;
    protected $customerID;
    protected $taxNum;
    protected $externalDocumentID;
    protected $currencyID;
    protected $conversionRate;


    public function __construct($customerID = "", $dateSent = "", $dateToPay = "", $dateServed = "",
                                $taxNum = "", $currencyID = "", $externalDocumentID = "")
    {
        $this->dateSent   = $dateSent;
        $this->dateToPay  = $dateToPay;
        $this->dateServed = $dateServed;
        $this->customerID = $customerID;
        $this->taxNum     = $taxNum;
        $this->currencyID = $currencyID;
        $this->externalDocumentID = $externalDocumentID;
    }

    public function dateSent($dateSent)
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    public function dateToPay($dateToPay)
    {
        $this->dateToPay = $dateToPay;

        return $this;
    }

    public function dateServed($dateServed)
    {
        $this->dateServed = $dateServed;

        return $this;
    }

    public function customerID($customerID)
    {
        $this->customerID = $customerID;

        return $this;
    }

    public function customerTaxNumber($taxNum)
    {
        $this->taxNum = $taxNum;

        return $this;
    }

    public function customInvoiceID($invoiceID)
    {
        $this->externalDocumentID = $invoiceID;

        return $this;
    }

    public function currencyID($currencyID)
    {
        $this->currencyID = $currencyID;

        return $this;
    }

    public function conversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;

        return $this;
    }

    public function toArray()
    {
        return [
            "date_sent"       => $this->dateSent,
            "date_to_pay"     => $this->dateToPay,
            "date_served"     => $this->dateServed,
            "id_partner"      => $this->customerID,
            "id_currency"     => $this->currencyID,
            "conv_rate"       => $this->conversionRate,
            "id_document_ext" => $this->externalDocumentID
        ];
    }

    public function toString()
    {
        return
            urlencode("date_sent")    . "=" . urlencode($this->dateSent) . "&" .
            urlencode("date_to_pay")    . "=" . urlencode($this->dateToPay) . "&" .
            urlencode("date_served")    . "=" . urlencode($this->dateServed) . "&" .
            urlencode("id_partner")    . "=" . urlencode($this->customerID) . "&" .
            urlencode("id_currency")    . "=" . urlencode($this->currencyID) . "&" .
            urlencode("conv_rate")    . "=" . urlencode($this->conversionRate) . "&" .
            urlencode("id_document_ext")    . "=" . urlencode($this->externalDocumentID);
    }
}