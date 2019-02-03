<?php

namespace Mare06xa\Beez\Classes;


use Illuminate\Support\Collection;

class Payments
{
    protected $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function add(Payment $payment)
    {
        $this->items->push($payment);
    }

    public function remove(Payment $payment)
    {
        $this->items->forget($payment);
    }

    public function toJSON()
    {
        return $this->items->toJson();
    }
}