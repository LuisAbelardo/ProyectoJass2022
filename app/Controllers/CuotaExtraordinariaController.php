<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use Laminas\Diactoros\ServerRequest;
use App\Libraries\LogMonolog;
use App\Models\CuotaExtraordinaria;


class CuotaExtraordinariaController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/cuotaextraordinaria/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    
    
    /**
     * Método muestra lista de cuotas extraordinarias
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListCuotasExtraordinarias(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) CuotaExtraordinaria::count();
        $pagination = CuotaExtraordinaria::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['cuotasExtraordinarias'] = CuotaExtraordinaria::offset($pagination['paginaOffset'])
                                                                    ->limit($pagination['paginaLimit'])
                                                                    ->orderBy('CUE_CODIGO', 'desc')
                                                                    ->get()->toArray();
        }else{
            $this->respuestaData['cuotasExtraordinarias'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'cuotaExtraordinariaList');
    }
    
    
    /**
     * Método muestra lista de cuotas extraordinarias según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListCuotasExtraordinarias(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar cuotas extraordinarias
        $cuotaExtraordinaria = CuotaExtraordinaria::join('CONTRATO', 'CUOTA_EXTRAORDINARIA.CTO_CODIGO', '=', 'CONTRATO.CTO_CODIGO')
                                                    ->join('PREDIO', 'CONTRATO.PRE_CODIGO', '=', 'PREDIO.PRE_CODIGO')
                                                    ->join('CLIENTE', 'PREDIO.CLI_CODIGO', '=', 'CLIENTE.CLI_CODIGO')
                                                    ->join('PROYECTO', 'CUOTA_EXTRAORDINARIA.PTO_CODIGO', '=', 'PROYECTO.PTO_CODIGO')
                                                    ->select('CUOTA_EXTRAORDINARIA.*', 'PROYECTO.PTO_CODIGO','CONTRATO.CTO_CODIGO');
        
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $cuotaExtraordinaria = $cuotaExtraordinaria->where('CUE_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterProyecto']) && $queryData['filterProyecto'] != ''){
            $cuotaExtraordinaria = $cuotaExtraordinaria->where('PROYECTO.PTO_CODIGO', '=', $queryData['filterProyecto']);
        }
        if(isset($queryData['filterContrato']) && $queryData['filterContrato'] != ''){
            $cuotaExtraordinaria = $cuotaExtraordinaria->where('CONTRATO.CTO_CODIGO', '=', $queryData['filterContrato']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $cuotaExtraordinaria = $cuotaExtraordinaria->where('CUE_ESTADO', '=', $queryData['filterEstado']);
        }
        if(isset($queryData['filterCliente']) && $queryData['filterCliente'] != ''){
            $cuotaExtraordinaria = $cuotaExtraordinaria->where('CLIENTE.CLI_DOCUMENTO', '=', $queryData['filterCliente']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $cuotaExtraordinaria->count();
        $pagination = CuotaExtraordinaria::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListCuotasExtra'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['cuotasExtraordinarias'] = $cuotaExtraordinaria->offset($pagination['paginaOffset'])
                                                                                    ->limit($pagination['paginaLimit'])
                                                                                    ->orderBy('CUE_CODIGO', 'desc')
                                                                                    ->get()->toArray();
        }else{
            $this->respuestaData['cuotasExtraordinarias'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'cuotaExtraordinariaList');
    }
    
}
  