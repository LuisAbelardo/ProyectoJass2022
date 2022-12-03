<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model{

    protected $table = 'TIPO_USUARIO';
    protected $primaryKey = 'TPU_CODIGO';

    const CREATED_AT = 'TPU_CREATED';
    const UPDATED_AT = 'TPU_UPDATED';

    public function users()
    {
        return $this->hasMany('App\Models\User', 'TPU_CODIGO');
    }
    
    public function cajas()
    {
        return $this->belongsToMany('App\Models\Caja', 'TIPO_USUARIO_CAJA', 'TPU_CODIGO', 'CAJ_CODIGO');
    }
}