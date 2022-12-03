<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model{
    
    protected $table = 'SERVICIO';
    protected $primaryKey = 'SRV_CODIGO';
    
    const CREATED_AT = 'SRV_CREATED';
    const UPDATED_AT = 'SRV_UPDATED';
    const DELETED_AT = 'SRV_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
}