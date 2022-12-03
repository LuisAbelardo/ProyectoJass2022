<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoUsuarioCaja extends Model{

    protected $table = 'TIPO_USUARIO_CAJA';
    protected $primaryKey = 'TUC_CODIGO';

    const CREATED_AT = 'TUC_CREATED';
    const UPDATED_AT = 'TUC_UPDATED';
    const DELETED_AT = 'TUC_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
}