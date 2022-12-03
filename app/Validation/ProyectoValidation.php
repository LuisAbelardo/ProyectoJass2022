<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ProyectoValidation extends BaseValidation{
    
    
    /**
     * Método valida datos para nuevo proyecto
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'nombre'                    => 'required',
            'montoPorContrato'          => 'required|numeric|min:0',
            'nroCuotasPorContrato'      => 'required|numeric|min:0',
            'descripcion'               => 'required'
        ]);
        
        $validation->setAliases([
            'montoPorContrato'          => 'monto por contrato',
            'nroCuotasPorContrato'      => 'número de cuotas por contrato'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para confirmar proyecto
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesConfirm(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de proyecto'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para anular proyecto
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesAnnular(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de proyecto'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}