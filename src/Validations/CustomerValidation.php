<?php

namespace Mare06xa\Beez\Validations;


use Illuminate\Support\Facades\Validator;

class CustomerValidation implements InvoiceValidation
{
    protected $rules = [
        'name'    => 'required|string',
        'street'  => 'string',
        'postal'  => 'alpha_dash',
        'city'    => 'string',
        'country' => 'string',
    ];

    protected $messages = [
        '*.required'  => 'is required.',
        '*.string'    => 'must be a string.',
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
        $errorMessage = "insertCustomer() error:\n\n";
        $messages = $this->validationData->getMessageBag()->getMessages();

        foreach ($messages as $field => $fieldMassages) {
            foreach ($fieldMassages as $fieldMessage) {
                $errorMessage .= "\t- " . $field . " " . $fieldMessage . "\n";
            }
        }

        return $errorMessage;
    }
}