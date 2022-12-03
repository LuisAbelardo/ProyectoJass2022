<?php namespace App\Controllers;

use App\Libraries\Session;
use App\Models\TipoUsuario;


class DashboardController extends BaseController{

    private $viewsPath = '/administration/dashboard/';
    
    /**
     * Método para cargar la pagina principal del administrador
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function index()
    {
        $oTipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $oTipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'dashboardAdmin');
    }
}