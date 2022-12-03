<?php namespace App\Validation;

use Rakit\Validation\Validator;

class TipoPredioValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo tipo de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'nombre'            => 'required'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar tipo de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric', 
            'nombre'             => 'required'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de tipo de predio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar tipo de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de tipo de predio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}