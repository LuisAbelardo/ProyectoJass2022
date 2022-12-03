<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class Proyecto extends Model{
    
    protected $table = 'PROYECTO';
    protected $primaryKey = 'PTO_CODIGO';
    
    const CREATED_AT = 'PTO_CREATED';
    const UPDATED_AT = 'PTO_UPDATED';
    
    use Pagination;
    
    public function cuotas()
    {
        return $this->hasMany('App\Models\CuotaExtraordinaria', 'CUE_CODIGO');
    }
    
}