<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\TipoPredioValidation;
use App\Models\TipoPredio;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\TipoUsoPredio;


class TipoPredioController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/tipopredio/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo tipo predio
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewTipoPredio()
    {
        return $this->renderHTML($this->viewsPath.'tipoPredioNew');
    }
    

    /**
     * Método guarda nuevo tipo predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewTipoPredio(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oTipoPredioValidation = new TipoPredioValidation();
        $validation = $oTipoPredioValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoTipoPredio'] = $postData;
        } else {
            
            // Los nombres de tipo de predio no deben repetirse (son llave unica)
            if (is_null(TipoPredio::where('TIP_NOMBRE', '=', $postData['nombre'])->first())) {
                
                try{
                    $oTipoPredio = new TipoPredio();
                    $oTipoPredio->TIP_NOMBRE = $postData['nombre'];
                    $oTipoPredio->save();
                    
                    // Redireccionamos a la vista detalle de tipo de predio
                    return new RedirectResponse('/tipopredio/detalle/'.$oTipoPredio->TIP_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Tipo de predio no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el tipo de predio'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de tipo de predio
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de tipo de predio no disponible'];
                $this->respuestaData['formNuevoTipoPredio'] = $postData;
            }

            
        }

        return $this->renderHTML($this->viewsPath.'tipoPredioNew');
    }
    
    /**
     * Método muestra lista de tipo de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListTiposPredio(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) TipoPredio::count();
        $pagination = TipoPredio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['tiposPredio'] = TipoPredio::offset($pagination['paginaOffset'])
                                                                ->limit($pagination['paginaLimit'])
                                                                ->orderBy('TIP_CODIGO', 'desc')
                                                                ->get()->toArray();
        }else{
            $this->respuestaData['tiposPredio'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioList');
    }
    
    
    /**
     * Método muestra lista de tipos de predio según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListTiposPredio(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar tipos de predio
        $oTipoPredio = new TipoPredio();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oTipoPredio = $oTipoPredio->where('TIP_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterNombre']) && $queryData['filterNombre'] != '') {
            $oTipoPredio = $oTipoPredio->where('TIP_NOMBRE', 'LIKE', $queryData['filterNombre'].'%');
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oTipoPredio->count();
        $pagination = TipoPredio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListTiposPredio'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['tiposPredio'] = $oTipoPredio->offset($pagination['paginaOffset'])
                                                                ->limit($pagination['paginaLimit'])
                                                                ->orderBy('TIP_CODIGO', 'desc')
                                                                ->get()->toArray();
        }else{
            $this->respuestaData['tiposPredio'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioList');
    }
    
    /**
     * Método muestra todos los datos de tipo de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailTipoPredio(ServerRequest $request)
    {
        $codeTipoPredio = (int) $request->getAttribute('tipoPredioId');
        
        $tipoPredio = TipoPredio::find($codeTipoPredio);
        
        if($tipoPredio != null){
            $this->respuestaData['tipoPredio'] = $tipoPredio->toArray();
            $this->respuestaData['tiposUsoPredio'] = $tipoPredio->tiposUso->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el tipo de predio solicitado'];
            $this->respuestaData['tipoPredioId'] = $codeTipoPredio;
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de tipo de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditTipoPredio(ServerRequest $request) {
        
        $codeTipoPredio = (int) $request->getAttribute('tipoPredioId');
        
        $tipoPredio = TipoPredio::find($codeTipoPredio);
        
        if($tipoPredio != null){
            $this->respuestaData['tipoPredio'] = $tipoPredio->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el tipo de predio solicitado'];
            $this->respuestaData['tipoPredioId'] = $codeTipoPredio;
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioEdit');
    }
    
    
    /**
     * Método para actualizar datos de tipo de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateTipoPredioData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oTipoPredioValidation = new TipoPredioValidation();
        $validation = $oTipoPredioValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarTipoPredio'] = $postData;
        } else {
            
            // Tipo de predio a editar
            $oTipoPredio = TipoPredio::find($postData['codigo']);
            
            if(!is_null($oTipoPredio)){
                
                // Los nombres de tipo de predio no deben repetirse (son llave unica)
                if (TipoPredio::where('TIP_NOMBRE', '=', $postData['nombre'])->where('TIP_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        $oTipoPredio->TIP_NOMBRE = $postData['nombre'];
                        $oTipoPredio->save();
                        
                        // Redireccionamos a la vista detalle de tipo de predio
                        return new RedirectResponse('/tipopredio/detalle/'.$oTipoPredio->TIP_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Tipo de predio no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de tipo de predio'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de tipo de predio
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de tipo de predio no disponible'];
                    $this->respuestaData['formEditarTipoPredio'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar tipo de predio a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el tipo de predio solicitado'];
                $this->respuestaData['formEditarTipoPredio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioEdit');
    }
    
    /**
     * Método elimina tipo de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deleteTipoPredio(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oTipoPredioValidation = new TipoPredioValidation();
        $validation = $oTipoPredioValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarTipoPredio'] = $postData;
        } else {
            
            // Tipo de predio a eliminar
            $oTipoPredio = TipoPredio::find($postData['codigo']);
            
            if(!is_null($oTipoPredio)){
                
                // Se eliminará el tipo de predio si no tiene algún "tipo de uso de predio" asociado
                $tipoUsoPredio = $oTipoPredio->tiposUso->first();
                if(is_null($tipoUsoPredio)){
                    
                    try{
                        $oTipoPredio->delete();
                        
                        // Redireccionamos a la vista lista de tipos de predio
                        return new RedirectResponse('/tipopredio/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Tipo de predio no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el tipo de predio'];
                    }
                    
                }else{
                    
                    // Enviando mensaje por no poder eliminar tipo de predio ya que tiene algún "tipo de uso de predio" asociado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['No se puede eliminar el tipo de predio por tener tipos de uso de predio asociados'];
                    $this->respuestaData['formEliminarTipoPredio'] = $postData;
                    
                    // Asignando datos  a la data a enviar
                    $this->respuestaData['tipoPredio'] = $oTipoPredio->toArray();
                    $this->respuestaData['tiposUsoPredio'] = $oTipoPredio->tiposUso->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar tipo de predio a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el tipo de predio solicitado'];
                $this->respuestaData['formEliminarTipoPredio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'tipoPredioDetail');
    }
    
}
  