<?php namespace App\Validation;

use Rakit\Validation\Validator;

class TipoUsoPredioValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo tipo de uso de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'nombre'            => 'required',
            'tarifaAgua'        => 'required|numeric',
            'tarifaDesague'     => 'required|numeric',
            'tipoPredio'        => 'required|numeric',
            'tarifaAmbos'       => 'required_if:tipoPredio,1|numeric',
            'tarifaManten'      => 'required_if:tipoPredio,1|numeric'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar tipo de uso de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric', 
            'nombre'             => 'required',
            'tarifaAgua'         => 'required|numeric',
            'tarifaDesague'      => 'required|numeric',
            'tipoPredio'         => 'required|numeric',
            'tarifaAmbos'       => 'required_if:tipoPredio,1|numeric',
            'tarifaManten'      => 'required_if:tipoPredio,1|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de tipo uso predio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar tipo de uso de predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de tipo uso predio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}