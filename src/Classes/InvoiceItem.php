<?php

namespace Mare06xa\Beez\Classes;


class InvoiceItem
{
    protected $itemTitle;
    protected $itemQuantity;
    protected $measuringUnit;
    protected $itemPrice;
    protected $VAT;
    protected $priceDiscount;
    protected $invoiceID;

    public function __construct($invoiceID, $title = "", $quantity = "", $measuringUnit = "",
                                $price = "", $VAT = "", $discount = "")
    {
        $this->itemTitle     = $title;
        $this->itemQuantity  = $quantity;
        $this->measuringUnit = $measuringUnit;
        $this->itemPrice     = $price;
        $this->VAT           = $VAT;
        $this->priceDiscount = $discount;
        $this->invoiceID     = $invoiceID;
    }

    public function title($itemTitle)
    {
        $this->itemTitle = $itemTitle;

        return $this;
    }

    public function quantity($itemQuantity)
    {
        $this->itemQuantity = $itemQuantity;

        return $this;
    }

    public function measuringUnit($measuringUnit)
    {
        $this->measuringUnit = $measuringUnit;

        return $this;
    }

    public function price($itemPrice)
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    public function VAT($VAT)
    {
        $this->VAT = $VAT;

        return $this;
    }

    public function discount($priceDiscount)
    {
        $this->priceDiscount = $priceDiscount;

        return $this;
    }

    public function invoiceID($invoiceID)
    {
        $this->invoiceID = $invoiceID;

        return $this;
    }

    public function toString()
    {
        return
            "title="           . $this->itemTitle .
            "qty="             . $this->itemQuantity .
            "mu="              . $this->measuringUnit .
            "price="           . $this->itemPrice .
            "vat="             . $this->VAT .
            "discount="        . $this->priceDiscount .
            "id_invoice_sent=" . $this->invoiceID;
    }

    public function toArray()
    {
        return [
            "title"           => $this->itemTitle,
            "qty"             => $this->itemQuantity,
            "mu"              => $this->measuringUnit,
            "price"           => $this->itemPrice,
            "vat"             => $this->VAT,
            "discount"        => $this->priceDiscount,
            "id_invoice_sent" => $this->invoiceID
        ];
    }
}