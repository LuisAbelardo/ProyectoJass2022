<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model{

    protected $table = 'USUARIO';
    protected $primaryKey = 'USU_CODIGO';

    const CREATED_AT = 'USU_CREATED';
    const UPDATED_AT = 'USU_UPDATED';
    const DELETED_AT = 'USU_DELETED';
    
    use Pagination;
    use SoftDeletes;

    public function tipoUsuario()
    {
        return $this->belongsTo('App\Models\TipoUsuario', 'TPU_CODIGO', 'TPU_CODIGO');
    }
    
}