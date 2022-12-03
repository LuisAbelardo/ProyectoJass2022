<?php namespace App\Validation;

use Rakit\Validation\Validator;

class IngresoValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo ingreso por pago de recibo
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNewPagoRecibo(array $data){

        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'reciboCodigo'      => 'required',
            'montoTotal'        => 'required|numeric|min:0',
            'montoRecibido'     => 'required|numeric|min:0',
            'comprobanteTipo'   => 'required|in:1,2,3',
            'comprobanteNro'    => 'required_if:comprobanteTipo:2,3',
            'caja'              => 'required|in:1,2'
        ]);
        
        $validation->setAliases([
            'reciboCodigo'             => 'codigo de recibo',
            'montoRecibido'            => 'monto recibido',
            'comprobanteTipo'          => 'tipo comprobante',
            'comprobanteNro'           => 'número de comprobante'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    /**
     * Método valida datos para registrar nuevo ingreso por pago de cuota extraordinaria
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNewPagoCuotaExtraordinaria(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'cuotaExtraCodigo'      => 'required',
            'montoTotal'            => 'required|numeric|min:0',
            'montoRecibido'         => 'required|numeric|min:0',
            'comprobanteTipo'       => 'required|in:1,2,3',
            'comprobanteNro'        => 'required_if:comprobanteTipo:2,3',
            'caja'                  => 'required|in:1,2'
        ]);
        
        $validation->setAliases([
            'cuotaExtraordinariaCodigo'         => 'codigo de cuota extraordinaria',
            'montoRecibido'                     => 'monto recibido',
            'comprobanteTipo'                   => 'tipo comprobante',
            'comprobanteNro'                    => 'número de comprobante'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para registrar nuevo ingreso por motivos diversos
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNewIngresoOtros(array $data){
        
        $validator = new Validator;
        
        // Validar campos compartidos (persona natural y juridica)
        $validation = $validator->make($data, [
            'montoTotal'                    => 'required|numeric|min:0',
            'montoRecibido'                 => 'required|numeric|min:0',
            'comprobanteTipo'               => 'required|in:1,2,3',
            'comprobanteNro'                => 'required_if:comprobanteTipo:2,3',
            'caja'                          => 'required|in:1,2',
            'descripcion'                   => 'required'
        ]);
        
        $validation->setAliases([
            'montoRecibido'                     => 'monto recibido',
            'comprobanteTipo'                   => 'tipo comprobante',
            'comprobanteNro'                    => 'número de comprobante'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para anular ingreso
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesAnnular(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de ingreso'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
}