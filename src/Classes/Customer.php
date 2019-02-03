<?php

namespace Mare06xa\Beez\Classes;


class Customer
{
    protected $name;
    protected $address;
    protected $postcode;
    protected $city;
    protected $country;

    public function __construct($name = "", $address = "", $postcode = "", $city = "", $country = "")
    {
        $this->name     = $name;
        $this->address  = $address;
        $this->postcode = $postcode;
        $this->city     = $city;
        $this->country  = $country;
    }

    public function name($personName)
    {
        $this->name = $personName;

        return $this;
    }

    public function address($streetAddress)
    {
        $this->address = $streetAddress;

        return $this;
    }

    public function postcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function city($city)
    {
        $this->city = $city;

        return $this;
    }

    public function country($country)
    {
        $this->country = $country;

        return $this;
    }

    public function toString()
    {
        return
            "name="    . $this->name .
            "street="  . $this->address .
            "postal="  . $this->postcode .
            "city="    . $this->city .
            "country=" . $this->country;
    }
}