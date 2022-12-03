<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ServicioAdicionalRcbValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo servicio adicional recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'descripcion'   => 'required',
            'costo'         => 'required|numeric|min:0',
            'contrato'      => 'required|numeric'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar servicio adicional recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'        => 'required|numeric', 
            'descripcion'   => 'required',
            'costo'         => 'required|numeric|min:0',
            'contrato'      => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de monto adicional'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar servicio adicional recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de monto adicional'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}