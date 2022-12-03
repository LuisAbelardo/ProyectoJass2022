<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Igv extends Model{
    
    protected $table = 'IGV';
    protected $primaryKey = 'IGV_CODIGO';
    
    const CREATED_AT = 'IGV_CREATED';
    const UPDATED_AT = 'IGV_UPDATED';
    
}