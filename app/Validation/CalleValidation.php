<?php namespace App\Validation;

use Rakit\Validation\Validator;

class CalleValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nueva calle
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'nombre'            => 'required',
            'sectores'          => 'required|array'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar calle
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric', 
            'nombre'             => 'required',
            'sectores'           => 'required|array'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de calle'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar calle
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de calle'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}