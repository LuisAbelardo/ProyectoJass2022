<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPredio extends Model{
    
    protected $table = 'TIPO_PREDIO';
    protected $primaryKey = 'TIP_CODIGO';
    
    const CREATED_AT = 'TIP_CREATED';
    const UPDATED_AT = 'TIP_UPDATED';
    const DELETED_AT = 'TIP_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function tiposUso()
    {
        return $this->hasMany('App\Models\TipoUsoPredio', 'TIP_CODIGO');
    }
    
}