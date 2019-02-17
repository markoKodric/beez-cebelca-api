<?php

namespace Mare06xa\Beez\Validations;


use Illuminate\Support\Facades\Validator;

class InvoiceItemValidation implements InvoiceValidation
{
    protected $rules = [
        'title'    => 'required|string',
        'qty'      => 'required|numeric',
        'mu'       => 'required|string',
        'price'    => 'required|numeric',
        'vat'      => 'required|numeric',
        'discount' => 'numeric',
    ];

    protected $messages = [
        '*.required'    => 'is required.',
        '*.string'      => 'must be a string.',
        '*.date_format' => 'must be in format :format.',
        '*.max'         => 'must be :max characters long.',
        '*.between'     => 'must be between :min and :max.',
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
        $errorMessage = "insertItems() error:\n\n";
        $messages = $this->validationData->getMessageBag()->getMessages();

        foreach ($messages as $field => $fieldMassages) {
            foreach ($fieldMassages as $fieldMessage) {
                $errorMessage .= "\t- " . $field . " " . $fieldMessage . "\n";
            }
        }

        return $errorMessage;
    }
}