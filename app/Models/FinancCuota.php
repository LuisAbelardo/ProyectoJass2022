<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancCuota extends Model{
    
    protected $table = 'FINANC_CUOTA';
    protected $primaryKey = 'FCU_CODIGO';
    
    const CREATED_AT = 'FCU_CREATED';
    const UPDATED_AT = 'FCU_UPDATED';
    
    
    public function financiamiento()
    {
        return $this->belongsTo('App\Models\Financiamiento', 'FTO_CODIGO', 'FTO_CODIGO');
    }
    
}