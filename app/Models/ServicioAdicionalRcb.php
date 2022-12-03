<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicioAdicionalRcb extends Model{
    
    protected $table = 'SERVICIO_ADICIONAL_RBO';
    protected $primaryKey = 'SAR_CODIGO';
    
    const CREATED_AT = 'SAR_CREATED';
    const UPDATED_AT = 'SAR_UPDATED';
    const DELETED_AT = 'SAR_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'CTO_CODIGO', 'CTO_CODIGO');
    }
    
}