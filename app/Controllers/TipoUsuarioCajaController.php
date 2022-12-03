<?php namespace App\Controllers;

use App\Models\TipoUsuarioCaja;
use App\Libraries\Session;


class TipoUsuarioCajaController extends BaseController{
    
    /*
     * Método verifica si un usuario tiene permiso para acceder a un caja
     * @param int $codigoCaja
     * @return boolean
     */
    public static function verifyUserAccessToCaja(int $codeCaja){
        
        $userHavePermision = false;
        
        $tipoUsuario = Session::getUserValue('rol_id');
        
        $permiso = TipoUsuarioCaja::where('TPU_CODIGO','=', $tipoUsuario)
                                ->where('CAJ_CODIGO', '=', $codeCaja)
                                ->get();
        
        if (!$permiso->isEmpty()) {
            $userHavePermision = true;
        }
        
        return  $userHavePermision;
        
    }
}