<?php

namespace Mare06xa\Beez\Validations;


use Illuminate\Support\Facades\Validator;

class InvoiceHeadValidation implements InvoiceValidation
{
    protected $rules = [
        'date_sent'       => 'required|date_format:d.m.Y',
        'date_served'     => 'required|date_format:d.m.Y',
        'date_to_pay'     => 'required|date_format:d.m.Y',
        'date_payed'      => 'string',
        'payment'         => 'string',
        'taxnum'          => 'string|max:11',
        'id_document_ext' => 'integer',
        'id_currency'     => 'integer|between:0,80',
        'conv_rate'       => 'required_with:id_currency|numeric',
    ];

    protected $messages = [
        '*.required'    => 'is required.',
        '*.string'      => 'must be a string.',
        '*.date_format' => 'must be a date in format :format.',
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
        $errorMessage = "insertHead() error:\n\n";
        $messages = $this->validationData->getMessageBag()->getMessages();

        foreach ($messages as $field => $fieldMassages) {
            foreach ($fieldMassages as $fieldMessage) {
                $errorMessage .= "\t- " . $field . " " . $fieldMessage . "\n";
            }
        }

        return $errorMessage;
    }
}