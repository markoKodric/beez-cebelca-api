<?php

namespace Mare06xa\Beez\Validations;


interface InvoiceValidation
{
    public function __construct($data);
    public function getMessage();
}