<?php

namespace Mare06xa\Beez\Validations;


use Illuminate\Support\Facades\Validator;

class PaymentValidation implements InvoiceValidation
{
    protected $rules = [
        'date_of'           => 'required|date_format:d.m.Y',
        'amount'            => 'numeric',
        'id_payment_method' => 'required|between:1,2'
    ];

    protected $messages = [
        '*.required'    => 'is required.',
        '*.date_format' => 'must be in format :date_format.',
        '*.between'     => 'must be between :between.',
        '*.numeric'     => 'must be numeric.'
    ];

    protected $data;

    protected $validationData;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function fails()
    {
        $this->validationData = Validator::make($this->data, $this->rules, $this->messages);

        return $this->validationData->fails();
    }

    public function getMessage() {
        $errorMessage = "insertPayment() error:\n\n";
        $messages = $this->validationData->getMessageBag()->getMessages();

        foreach ($messages as $field => $fieldMassages) {
            foreach ($fieldMassages as $fieldMessage) {
                $errorMessage .= "\t- " . $field . " " . $fieldMessage . "\n";
            }
        }

        return $errorMessage;
    }
}