<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calle extends Model{
    
    protected $table = 'CALLE';
    protected $primaryKey = 'CAL_CODIGO';
    
    const CREATED_AT = 'CAL_CREATED';
    const UPDATED_AT = 'CAL_UPDATED';
    const DELETED_AT = 'CAL_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function sectores()
    {
        return $this->belongsToMany('App\Models\Sector', 'SECTOR_CALLE', 'CAL_CODIGO', 'STR_CODIGO');
    }
    
    public function predios()
    {
        return $this->hasMany('App\Models\Predio', 'CAL_CODIGO');
    }
    
}