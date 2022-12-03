<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model{
    
    protected $table = 'SECTOR';
    protected $primaryKey = 'STR_CODIGO';
    
    const CREATED_AT = 'STR_CREATED';
    const UPDATED_AT = 'STR_UPDATED';
    const DELETED_AT = 'STR_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function calles()
    {
        return $this->belongsToMany('App\Models\Calle', 'SECTOR_CALLE','STR_CODIGO', 'CAL_CODIGO');
    }
    
    public function predios()
    {
        return $this->hasMany('App\Models\Predio', 'STR_CODIGO');
    }
    
}