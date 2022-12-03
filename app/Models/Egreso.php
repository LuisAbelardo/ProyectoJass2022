<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class Egreso extends Model{
    
    protected $table = 'EGRESO';
    protected $primaryKey = 'EGR_CODIGO';
    
    const CREATED_AT = 'EGR_CREATED';
    const UPDATED_AT = 'EGR_UPDATED';
    
    use Pagination;
    
    public function caja()
    {
        return $this->belongsTo('App\Models\Caja', 'CAJ_CODIGO', 'CAJ_CODIGO');
    }
    
}