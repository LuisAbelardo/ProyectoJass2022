<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model{
    
    protected $table = 'CONTRATO';
    protected $primaryKey = 'CTO_CODIGO';
    
    const CREATED_AT = 'CTO_CREATED';
    const UPDATED_AT = 'CTO_UPDATED';
    
    use Pagination;
    
    public function predio()
    {
        return $this->belongsTo('App\Models\Predio', 'PRE_CODIGO', 'PRE_CODIGO');
    }
    
    public function tipoUsoPredio()
    {
        return $this->belongsTo('App\Models\TipoUsoPredio', 'TUP_CODIGO', 'TUP_CODIGO');
    }
    
    public function servicios()
    {
        return $this->belongsToMany('App\Models\Servicio', 'SERVICIO_CONTRATO', 'CTO_CODIGO', 'SRV_CODIGO');
    }
    
}