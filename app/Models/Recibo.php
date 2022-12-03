<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recibo extends Model{
    
    protected $table = 'RECIBO';
    protected $primaryKey = 'RBO_CODIGO';
    
    const CREATED_AT = 'RBO_CREATED';
    const UPDATED_AT = 'RBO_UPDATED';
    const DELETED_AT = 'RBO_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'CTO_CODIGO', 'CTO_CODIGO');
    }
    
}