<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class CuotaExtraordinaria extends Model{
    
    protected $table = 'CUOTA_EXTRAORDINARIA';
    protected $primaryKey = 'CUE_CODIGO';
    
    const CREATED_AT = 'CUE_CREATED';
    const UPDATED_AT = 'CUE_UPDATED';
    
    use Pagination;
    
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'CTO_CODIGO', 'CTO_CODIGO');
    }
    
    public function proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto', 'PTO_CODIGO', 'PTO_CODIGO');
    }
    
}