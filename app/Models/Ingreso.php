<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class Ingreso extends Model{
    
    protected $table = 'INGRESO';
    protected $primaryKey = 'IGR_CODIGO';
    
    const CREATED_AT = 'IGR_CREATED';
    const UPDATED_AT = 'IGR_UPDATED';
    
    use Pagination;
    
    public function caja()
    {
        return $this->belongsTo('App\Models\Caja', 'CAJ_CODIGO', 'CAJ_CODIGO');
    }
    
}