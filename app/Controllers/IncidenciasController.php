<?php namespace App\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;

class IncidenciasController extends BaseController{

    /**
     * Método para redireccionar al login porque el usurio
     * no esta autorizado para acceder al sistema
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function noAutorizado()
    {
        return new RedirectResponse('/login');
    }
    
    
    /**
     * Método para informar al usuario que no tiene los
     * permisos suficientes para ver el contenido solicitado
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function permisosInsuficientes()
    {
        $this->respuestaCodigo = 403;
        $this->respuestaEstado = 'permisos insuficientes para acceder al recurso';
        $this->respuestaEstadoDetalle = [];
        
        return $this->renderHTML('/administration/permisosInsuficientes');
    }
    
    /**
     * Método para informar al usuario que el recurso solicitado
     * no fue encontrado
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function recursoNoEncontrado()
    {
        $this->respuestaCodigo = 404;
        $this->respuestaEstado = 'recurso no encontrado';
        $this->respuestaEstadoDetalle = [];
        
        return $this->renderHTML('404');
    }
}