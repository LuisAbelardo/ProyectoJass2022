<?php namespace App\Validation;

use Rakit\Validation\Validator;

class FinanciamientoValidation extends BaseValidation{
    
    /**
     * Método valida datos de recibo para mostrar formulario nuevo financiamiento
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesRboForFinanciamiento(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'rboVencidos'      => 'required|array',
        ]);
        
        $validation->setAliases([
            'rboVencidos'      => 'recibos vencidos'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para registrar nuevo financiamiento de recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'cuotaInicial'      => 'required|numeric|min:0',
            'nroCuotas'         => 'required|numeric|min:0',
            'observacion'       => 'present'
        ]);
        
        $validation->setAliases([
            'cuotaInicial'      => 'cuota inicial',
            'nroCuotas'         => 'número de cuotas'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para confirmar financiamiento de recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesConfirm(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de financiamiento'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para anular financiamiento de recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesAnnular(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de financiamiento'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}