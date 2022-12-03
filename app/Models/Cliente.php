<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model{
    
    protected $table = 'CLIENTE';
    protected $primaryKey = 'CLI_CODIGO';
    
    const CREATED_AT = 'CLI_CREATED';
    const UPDATED_AT = 'CLI_UPDATED';
    const DELETED_AT = 'CLI_DELETED';
    
    use Pagination;
    use SoftDeletes;
    
    public function predios()
    {
        return $this->hasMany('App\Models\Predio', 'CLI_CODIGO');
    }
}