<?php

namespace Mare06xa\Beez\Classes;


class FiscalizeData
{
    protected $invoiceID;
    protected $locationID;
    protected $doFiscalize;
    protected $operatorTaxID;
    protected $operatorName;
    protected $testMode;
    protected $title;
    protected $documentType;

    /*
     * Document types
     */
    const INVOICE     = 0;
    const CREDIT_NOTE = 2;
    const ADVANCED_PAYMENT_INVOICE = 3;


    public function __construct($invoiceID, $locationID = "", $doFiscalize = 1, $operatorTaxID = "",
                                $operatorName = "", $testMode = false, $title = "", $documentType = self::INVOICE)
    {
        $this->invoiceID     = $invoiceID;
        $this->locationID    = $locationID;
        $this->doFiscalize   = $doFiscalize;
        $this->operatorTaxID = $operatorTaxID;
        $this->operatorName  = $operatorName;
        $this->testMode      = $testMode;
        $this->title         = $title;
        $this->documentType  = $documentType;
    }

    public function invoiceID($invoiceID)
    {
        $this->invoiceID = $invoiceID;

        return $this;
    }

    public function locationID($locationID)
    {
        $this->locationID = $locationID;

        return $this;
    }

    public function fiscalize($fiscalize)
    {
        $this->doFiscalize = $fiscalize;

        return $this;
    }

    public function operatorTaxID($operatorTaxID)
    {
        $this->operatorTaxID = $operatorTaxID;

        return $this;
    }

    public function operatorName($operatorName)
    {
        $this->operatorName = $operatorName;

        return $this;
    }

    public function testMode($testMode)
    {
        $this->testMode = $testMode;

        return $this;
    }

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    public function documentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function toString()
    {
        if ($this->locationID && is_int($this->locationID)) {
            return
                "id="          . $this->invoiceID .
                "id_location=" . $this->locationID .
                "fiscalize="   . $this->doFiscalize .
                "op-tax-id="   . $this->operatorTaxID .
                "op-name="     . $this->operatorName .
                "test_mode="   . $this->testMode;
        } else {
            return
                "id="      . $this->invoiceID .
                "title="   . $this->title .
                "doctype=" . $this->documentType;
        }
    }
}