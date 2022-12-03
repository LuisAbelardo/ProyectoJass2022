<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ReporteValidation extends BaseValidation{

    public function veryRulesGetRecibos($data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'month'             => 'required|in:ENERO,FEBRERO,MARZO,ABRIL,MAYO,JUNIO,JULIO,AGOSTO,SEPTIEMBRE,OCTUBRE,NOVIEMBRE,DICIEMBRE',
            'year'              => 'required|digits:4',
            'sector'            => 'required|numeric'
        ]);

        $validation->setAliases([
            'month' => 'mes',
            'year' => 'año'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    public function veryRulesGetArqueoDiario($data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'date'             => 'required|date:Y-m-d'
        ]);
        
        $validation->setAliases([
            'date' => 'fecha'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    public function veryRulesGetArqueoSemanal($data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'fechaInicio'             => 'required|date:Y-m-d',
            'fechaFin'             => 'required|date:Y-m-d'
        ]);
        
        $validation->setAliases([
            'fechaInicio' => 'fecha de inicio',
            'fechaFin' => 'fecha de fin'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}