<?php namespace App\Validation;

use Rakit\Validation\Validator;

class TransferenciaValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar iniciar nueva transferencia
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'cajaEmisor'            => 'required|in:1,2,3',
            'cajaReceptor'          => 'required|in:1,2,3',
            'monto'                 => 'required|numeric|min:0',
            'descripcion'           => 'required'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
}