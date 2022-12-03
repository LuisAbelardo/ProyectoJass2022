<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\TipoUsoPredioValidation;
use App\Models\TipoPredio;
use App\Models\TipoUsoPredio;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\Contrato;


class TipoUsoPredioController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/tipousopredio/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo tipo de uso de predio
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewTipoUsoPredio()
    {
        // Asignar los tipo de predios a la data a enviar
        $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioNew');
    }
    

    /**
     * Método guarda nuevo tipo de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewTipoUsoPredio(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oTipoUsoPredioValidation = new TipoUsoPredioValidation();
        $validation = $oTipoUsoPredioValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoTipoUsoPredio'] = $postData;
        } else {
            
            // Los nombres de tipo de uso de predio no deben repetirse (son llave unica)
            if (is_null(TipoUsoPredio::where('TUP_NOMBRE', '=', $postData['nombre'])->first())) {
                
                try{
                    $oTipoUsoPredio = new TipoUsoPredio();
                    $oTipoUsoPredio->TUP_NOMBRE = $postData['nombre'];
                    $oTipoUsoPredio->TUP_TARIFA_AGUA = $postData['tarifaAgua'];
                    $oTipoUsoPredio->TUP_TARIFA_DESAGUE = $postData['tarifaDesague'];
                    $oTipoUsoPredio->TIP_CODIGO = $postData['tipoPredio'];
                    $oTipoUsoPredio->TUP_TARIFA_AMBOS = (isset($postData['tarifaAmbos'])) ? $postData['tarifaAmbos'] : ($postData['tarifaAgua'] + $postData['tarifaDesague']);
                    // $oTipoUsoPredio->TUP_TARIFA_AMBOS = $postData['tarifaAmbos'];
                    $oTipoUsoPredio->TUP_TARIFA_MANTENIMIENTO = (isset($postData['tarifaManten'])) ? $postData['tarifaManten'] : 0;
                    //$oTipoUsoPredio->TUP_TARIFA_MANTENIMIENTO = $postData['tarifaManten'];
                    $oTipoUsoPredio->save();
                    
                    // Redireccionamos a la vista detalle de tipo de uso de predio
                    return new RedirectResponse('/tipousopredio/detalle/'.$oTipoUsoPredio->TUP_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Tipo de uso de predio no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el Tipo de uso de predio'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de tipo de uso de predio
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de Tipo de uso de predio no disponible'];
                $this->respuestaData['formNuevoTipoUsoPredio'] = $postData;
            }

            
        }

        // Asignar las tipos de predios a la data a enviar
        $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioNew');
    }
    
    /**
     * Método muestra lista de tipos de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListTiposUsoPredio(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) TipoUsoPredio::count();
        $pagination = TipoUsoPredio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['tiposUsoPredio'] = TipoUsoPredio::offset($pagination['paginaOffset'])
                                                                        ->limit($pagination['paginaLimit'])
                                                                        ->orderBy('TUP_CODIGO', 'desc')
                                                                        ->get()->toArray();
        }else{
            $this->respuestaData['tiposUsoPredio'] = [];
        }
        
        // Asignar los tipos de predio a la data a enviar
        $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioList');
    }
    
    
    /**
     * Método muestra lista de tipos de uso de predio según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListTiposUsoPredio(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar tipos de uso de predio
        $oTipoUsoPredio = new TipoUsoPredio();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oTipoUsoPredio = $oTipoUsoPredio->where('TUP_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterNombre']) && $queryData['filterNombre'] != '') {
            $oTipoUsoPredio = $oTipoUsoPredio->where('TUP_NOMBRE', '=', $queryData['filterNombre']);
        }
        if(isset($queryData['filterTipoPredio']) && $queryData['filterTipoPredio'] != ''
                && is_numeric($queryData['filterTipoPredio']) && $queryData['filterTipoPredio'] != -1) {
            $oTipoUsoPredio = $oTipoUsoPredio->where('TIP_CODIGO', '=', $queryData['filterTipoPredio']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oTipoUsoPredio->count();
        $pagination = TipoUsoPredio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListTiposUsoPredio'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['tiposUsoPredio'] = $oTipoUsoPredio->offset($pagination['paginaOffset'])
                                                                    ->limit($pagination['paginaLimit'])
                                                                    ->orderBy('TUP_CODIGO', 'desc')
                                                                    ->get()->toArray();
        }else{
            $this->respuestaData['tiposUsoPredio'] = [];
        }
        
        // Asignar los tipos de predio a la data a enviar
        $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioList');
    }
    
    /**
     * Método muestra todos los datos de tipo de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailTipoUsoPredio(ServerRequest $request)
    {
        $codeTipoUsoPredio = (int) $request->getAttribute('tipoUsoPredioId');
        
        $tipoUsoPredio = TipoUsoPredio::find($codeTipoUsoPredio);
        
        if($tipoUsoPredio != null){
            $this->respuestaData['tipoUsoPredio'] = $tipoUsoPredio->toArray();
            $this->respuestaData['tipoPredio'] = $tipoUsoPredio->tipoPredio->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el tipo de uso de predio solicitado'];
            $this->respuestaData['tipoUsoPredioId'] = $codeTipoUsoPredio;
        }
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de tipo de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditTipoUsoPredio(ServerRequest $request) {
        
        $codeTipoUsoPredio= (int) $request->getAttribute('tipoUsoPredioId');
        
        $tipoUsoPredio = TipoUsoPredio::find($codeTipoUsoPredio);
        
        if($tipoUsoPredio != null){
            $this->respuestaData['tipoUsoPredio'] = $tipoUsoPredio->toArray();
            $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el tipo de uso de predio solicitado'];
            $this->respuestaData['tipoUsoPredioId'] = $codeTipoUsoPredio;
        }
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioEdit');
    }
    
    
    /**
     * Método para actualizar datos de tipo de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateTipoUsoPredioData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oTipoUsoPredioValidation = new TipoUsoPredioValidation();
        $validation = $oTipoUsoPredioValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarTipoUsoPredio'] = $postData;
        } else {
            
            // Tipo de uso de predio a editar
            $oTipoUsoPredio = TipoUsoPredio::find($postData['codigo']);
            
            if(!is_null($oTipoUsoPredio)){
                
                // Los nombres de tipo de uso de predio no deben repetirse (son llave unica)
                if (TipoUsoPredio::where('TUP_NOMBRE', '=', $postData['nombre'])->where('TUP_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        $oTipoUsoPredio->TUP_NOMBRE = $postData['nombre'];
                        $oTipoUsoPredio->TUP_TARIFA_AGUA = $postData['tarifaAgua'];
                        $oTipoUsoPredio->TUP_TARIFA_DESAGUE = $postData['tarifaDesague'];
                        $oTipoUsoPredio->TIP_CODIGO = $postData['tipoPredio'];
                        $oTipoUsoPredio->TUP_TARIFA_AMBOS = (isset($postData['tarifaAmbos'])) ? $postData['tarifaAmbos'] : ($postData['tarifaAgua'] + $postData['tarifaDesague']);
                        $oTipoUsoPredio->TUP_TARIFA_MANTENIMIENTO = (isset($postData['tarifaManten'])) ? $postData['tarifaManten'] : 0;
                        $oTipoUsoPredio->save();
                        
                        // Redireccionamos a la vista detalle de tipo de uso de predio
                        return new RedirectResponse('/tipousopredio/detalle/'.$oTipoUsoPredio->TUP_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Tipo de uso de predio no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de tipo de uso de predio'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de tipo de uso de predio
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de tipo de uso de predio no disponible'];
                    $this->respuestaData['formEditarTipoUsoPredio'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar tipo de uso de predio a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el tipo de uso de predio solicitado'];
                $this->respuestaData['formEditarTipoUsoPredio'] = $postData;
            }
            
        }
        
        // Asignar los tipo de predios a la data a enviar
        $this->respuestaData['tiposPredio'] = TipoPredio::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioEdit');
    }
    
    /**
     * Método elimina tipo de uso de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deleteTipoUsoPredio(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oTipoUsoPredioValidation = new TipoUsoPredioValidation();
        $validation = $oTipoUsoPredioValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarTipoUsoPredio'] = $postData;
        } else {
            
            // Tipo de uso de predio a eliminar
            $oTipoUsoPredio = TipoUsoPredio::find($postData['codigo']);
            if(!is_null($oTipoUsoPredio)){
                
                // El tipo de uso predio solo se podra eliminar si no se incluyo en algun contrato
                $oContrato = Contrato::where('TUP_CODIGO', '=', $oTipoUsoPredio->TUP_CODIGO)->first();
                if (is_null($oContrato)) {
                    
                    try{
                        $oTipoUsoPredio->delete();
                        
                        // Redireccionamos a la vista lista de tipos de uso de predios
                        return new RedirectResponse('/tipousopredio/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Tipo de uso de predio no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el tipo de uso de predio'];
                    }
                    
                }else{
                    
                    // Controlando error porque el tipo de uso predio no debe eliminarse por estar en un contrato
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['El tipo de uso de predio no puede eliminarse ya que éste fue incluido en un contrato'];
                    $this->respuestaData['formEliminarTipoUsoPredio'] = $postData;
                    
                    $this->respuestaData['tipoUsoPredio'] = $oTipoUsoPredio->toArray();
                    $this->respuestaData['tipoPredio'] = $oTipoUsoPredio->tipoPredio->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar tipo de uso de predio a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el tipo de uso de predio solicitado'];
                $this->respuestaData['formEliminarTipoUsoPredio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'tipoUsoPredioDetail');
    }
    
}
  