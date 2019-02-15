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

    public function toArray()
    {
        return [
            'name' => $this->name,
            'street' => $this->address,
            'postal' => $this->postcode,
            'city' => $this->city,
            'country'
        ];
    }

    public function toString()
    {
        return
            urlencode("name")    . "=" . urlencode($this->name) . "&" .
            urlencode("street")  . "=" . urlencode($this->address) . "&" .
            urlencode("postal")  . "=" . urlencode($this->postcode) . "&" .
            urlencode("city")    . "=" . urlencode($this->city) . "&" .
            urlencode("country") . "=" . urlencode($this->country);
    }
}