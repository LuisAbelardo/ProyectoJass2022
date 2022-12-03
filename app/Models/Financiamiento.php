<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class Financiamiento extends Model{
    
    protected $table = 'FINANCIAMIENTO';
    protected $primaryKey = 'FTO_CODIGO';
    
    const CREATED_AT = 'FTO_CREATED';
    const UPDATED_AT = 'FTO_UPDATED';
    
    use Pagination;
    
    public function cuotas()
    {
        return $this->hasMany('App\Models\FinancCuota', 'FTO_CODIGO');
    }
    
    public function recibos()
    {
        return $this->hasMany('App\Models\Recibo', 'FTO_CODIGO');
    }
    
}