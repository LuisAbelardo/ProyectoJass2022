<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectorCalle extends Model{
    
    protected $table = 'SECTOR_CALLE';
    protected $primaryKey = 'STC_CODIGO';
    
    const CREATED_AT = 'STC_CREATED';
    const UPDATED_AT = 'STC_UPDATED';
    const DELETED_AT = 'STC_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
}