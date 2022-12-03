<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoUsoPredio extends Model{
    
    protected $table = 'TIPO_USO_PREDIO';
    protected $primaryKey = 'TUP_CODIGO';
    
    const CREATED_AT = 'TUP_CREATED';
    const UPDATED_AT = 'TUP_UPDATED';
    const DELETED_AT = 'TUP_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function tipoPredio()
    {
        return $this->belongsTo('App\Models\TipoPredio', 'TIP_CODIGO', 'TIP_CODIGO');
    }
    
}