<?php namespace App\Validation;

use Rakit\Validation\Validator;

class EgresoValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo egreso por pago de recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'monto'             => 'required|numeric|min:0',
            'comprobanteTipo'   => 'required|in:1,2,3,4',
            'comprobanteNro'    => 'required_if:comprobanteTipo:1,2,3',
            'caja'              => 'required|in:1,2,3',
            'descripcion'       => 'required',
        ]);
        
        $validation->setAliases([
            'comprobanteTipo'          => 'tipo comprobante',
            'comprobanteNro'           => 'número de comprobante'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
   
    
    
    /**
     * Método valida datos para anular egreso
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesAnnular(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de egreso'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}