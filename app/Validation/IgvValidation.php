<?php namespace App\Validation;

use Rakit\Validation\Validator;

class IgvValidation extends BaseValidation{

    public function veryRulesUpdate($data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'igv'                  => 'required|numeric|min:0'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
}