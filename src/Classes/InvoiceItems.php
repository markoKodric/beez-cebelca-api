<?php

namespace Mare06xa\Beez\Classes;

use Illuminate\Support\Collection;

class InvoiceItems
{
    protected $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function add(InvoiceItem $invoiceItem)
    {
        $this->items->push($invoiceItem);
    }

    public function remove(InvoiceItem $invoiceItem)
    {
        $this->items->forget($invoiceItem);
    }

    public function toJSON()
    {
        return $this->items->toJson();
    }
}