<?php

namespace Mare06xa\Beez\Classes;


class Result
{
    protected $resultData;


    public function __construct($result)
    {
        $this->resultData = $result[0];
    }

    public function getData()
    {
        return $this->resultData;
    }

    public function setDataParam($paramKey, $paramValue)
    {
        $this->resultData[$paramKey] = $paramValue;

        return $this;
    }
}