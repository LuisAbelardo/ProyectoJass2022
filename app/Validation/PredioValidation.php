<?php namespace App\Validation;

use Rakit\Validation\Validator;

class PredioValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'direccion'         => 'required|max:256',
            'calle'             => 'required|numeric',
            'cliente'           => 'required|numeric',
            'habitada'          => 'present|default:-1|in:-1,si,no',
            'materialConst'     => 'present|default:-1|in:-1,noble,adobe,madera,no aplicable',
            'pisos'             => 'present|default:-1|numeric',
            'familias'          => 'present|default:-1|numeric',
            'habitantes'        => 'present|default:-1|numeric',
            'pozoTabular'       => 'present|default:-1|in:-1,si,no',
            'piscina'           => 'present|default:-1|in:-1,si,no'
        ]);
        
        $validation->setAliases([
            'materialConst'     => 'material de construcción',
            'pozoTabular'       => 'poso tabular'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'            => 'required|numeric', 
            'direccion'         => 'required|max:256',
            'calle'             => 'required|numeric',
            'cliente'           => 'required|numeric',
            'habitada'          => 'present|default:-1|in:-1,si,no',
            'materialConst'     => 'present|default:-1|in:-1,noble,adobe,madera,no aplicable',
            'pisos'             => 'present|default:-1|numeric',
            'familias'          => 'present|default:-1|numeric',
            'habitantes'        => 'present|default:-1|numeric',
            'pozoTabular'       => 'present|default:-1|in:-1,si,no',
            'piscina'           => 'present|default:-1|in:-1,si,no'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de predio',
            'materialConst'     => 'material de construcción',
            'pozoTabular'       => 'poso tabular'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar predio
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de predio'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}