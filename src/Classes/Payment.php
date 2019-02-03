<?php

namespace Mare06xa\Beez\Classes;


class Payment
{
    protected $paymentDate;
    protected $amountPaid;
    protected $methodID;
    protected $invoiceID;
    protected $paymentNote;

    /*
     * Payment methods
     */
    const CASH        = 1;
    const CREDIT_CARD = 2;


    public function __construct($invoiceID, $paymentDate = "", $amount = "", $methodID = self::CASH, $note = "")
    {
        $this->paymentDate = $paymentDate;
        $this->amountPaid  = $amount;
        $this->methodID    = $methodID;
        $this->invoiceID   = $invoiceID;
        $this->paymentNote = $note;
    }

    public function date($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function amount($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    public function methodID($methodID)
    {
        $this->methodID = $methodID;

        return $this;
    }

    public function invoiceID($invoiceID)
    {
        $this->invoiceID = $invoiceID;

        return $this;
    }

    public function note($paymentNote)
    {
        $this->paymentNote = $paymentNote;

        return $this;
    }

    public function toString()
    {
        return
            "date_of="           . $this->paymentDate .
            "amount="            . $this->amountPaid .
            "id_payment_method=" . $this->methodID .
            "id_invoice_sent="   . $this->invoiceID;
    }

    public function toArray()
    {
        return [
            "date_of="           => $this->paymentDate,
            "amount="            => $this->amountPaid,
            "id_payment_method=" => $this->methodID,
            "id_invoice_sent="   => $this->invoiceID
        ];
    }
}