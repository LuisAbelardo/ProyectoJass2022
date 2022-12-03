<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ServicioValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo servicio
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
     * Método valida datos para actualizar servicio
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
            'codigo'          => 'codigo de servicio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar servicio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de servicio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}