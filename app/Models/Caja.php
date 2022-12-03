<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Pagination;

class Caja extends Model{
    
    protected $table = 'CAJA';
    protected $primaryKey = 'CAJ_CODIGO';
    
    const CREATED_AT = 'CAJ_CREATED';
    const UPDATED_AT = 'CAJ_UPDATED';
    
    use Pagination;
    
}