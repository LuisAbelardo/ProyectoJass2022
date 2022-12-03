<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicioContrato extends Model{
    
    protected $table = 'SERVICIO_CONTRATO';
    protected $primaryKey = 'SRC_CODIGO';
    
    const CREATED_AT = 'SRC_CREATED';
    const UPDATED_AT = 'SRC_UPDATED';
    
    use Pagination;
    
}