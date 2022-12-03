<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ClienteValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo cliente
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
        
        // Validar campos compartidos (persona natural y juridica)
        $validation = $validator->make($data, [
            'tipo'           => 'required|in:1,2',
            'departamento'   => 'required',
            'provincia'      => 'required',
            'distrito'       => 'required',
            'direccion'      => 'required',
            'telefono'       => 'present',
            'email'          => 'present|default:sinemail@gmail.com|email'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();
        
        // Validar campos dependiendo si es persona natural o juridica
        if (!$validation->fails()) {
            
            if($data['tipo'] == 1){
                $validation = $validator->make($data, [
                    'documento'         => 'required|digits:8',
                    'nombres'           => 'required',
                    'fechanacimiento'   => 'required|date:Y-m-d'
                ]);
                
                $validation->setAliases([
                    'documento'         => 'DNI',
                    'nombres'           => 'nombre',
                    'fechanacimiento'   => 'fecha de nacimiento'
                ]);
            }else{
                $validation = $validator->make($data, [
                    'documento'             => 'required|digits:11',
                    'nombres'               => 'required',
                    'representantelegal'    => 'required'
                ]);
                
                $validation->setAliases([
                    'documento'             => 'RUC',
                    'nombres'               => 'razón social',
                    'representantelegal'    => 'representante legal'
                ]);
            }
            
            $validation->setMessages($this->errorsMsjSpanish);
            $validation->validate();
        } 

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar cliente
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        // Validar campos compartidos (persona natural y juridica)
        $validation = $validator->make($data, [
            'codigo'         => 'required|numeric',
            'tipo'           => 'required',
            'departamento'   => 'required',
            'provincia'      => 'required',
            'distrito'       => 'required',
            'direccion'      => 'required',
            'telefono'       => 'present',
            'email'          => 'present|email'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de cliente'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        // Validar campos dependiendo si es persona natural o juridica
        if (!$validation->fails()) {
            
            if($data['tipo'] == 1){
                $validation = $validator->make($data, [
                    'documento'         => 'required|digits:8',
                    'nombres'           => 'required',
                    'fechanacimiento'   => 'required|date:Y-m-d'
                ]);
                
                $validation->setAliases([
                    'documento'         => 'DNI',
                    'nombres'           => 'nombre',
                    'fechanacimiento'   => 'fecha de nacimiento'
                ]);
            }else{
                $validation = $validator->make($data, [
                    'documento'             => 'required|digits:11',
                    'nombres'               => 'required',
                    'representantelegal'    => 'required'
                ]);
                
                $validation->setAliases([
                    'documento'             => 'RUC',
                    'nombres'               => 'razón social',
                    'representantelegal'    => 'representante legal'
                ]);
            }
            
            $validation->setMessages($this->errorsMsjSpanish);
            $validation->validate();
        } 
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar cliente
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de cliente'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}