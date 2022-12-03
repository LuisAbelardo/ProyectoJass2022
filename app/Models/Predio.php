<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Predio extends Model{
    
    protected $table = 'PREDIO';
    protected $primaryKey = 'PRE_CODIGO';
    
    const CREATED_AT = 'PRE_CREATED';
    const UPDATED_AT = 'PRE_UPDATED';
    const DELETED_AT = 'PRE_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function calle()
    {
        return $this->belongsTo('App\Models\Calle', 'CAL_CODIGO', 'CAL_CODIGO');
    }
    
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'CLI_CODIGO', 'CLI_CODIGO');
    }
    
    public function contratos()
    {
        return $this->hasMany('App\Models\Contrato', 'PRE_CODIGO');
    }
    
}