<?php

namespace Mare06xa\Beez\Validations;


use Illuminate\Support\Facades\Validator;

class PreviousCallValidation
{
    protected $rules;
    protected $messages;
    protected $data;
    protected $validationData;
    protected $validator;
    protected $previousCall;

    public function __construct($result, $previousCall)
    {
        $this->data         = $result;
        $this->previousCall = $previousCall;
        $this->messages = [
            '*.present' => 'must be present in data if ' . $previousCall . ' was not called.'
        ];
    }

    public function fails()
    {
        $this->validationData = true;
        $this->validator = new Validator();

        foreach ($this->data as $key => $array) {
            $this->validator = Validator::make($array, [$key => 'present'], $this->messages);

            $this->validationData =
                $this->validationData &&
                $this->validator->fails();
        }

        return $this->validationData;
    }

    public function getMessage() {
        $errorMessage = "";
        $messages = $this->validator->getMessageBag()->getMessages();

        foreach ($messages as $field => $fieldMassages) {
            foreach ($fieldMassages as $fieldMessage) {
                $errorMessage .= "\t- " . $field . " " . $fieldMessage . "\n";
            }
        }

        return $errorMessage;
    }
}