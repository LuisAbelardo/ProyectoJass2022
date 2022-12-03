<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\PredioValidation;
use App\Models\Calle;
use App\Models\Predio;
use App\Models\Cliente;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\Contrato;
use App\Models\CuotaExtraordinaria;


class PredioController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/predio/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo predio
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewPredio()
    {
        // Asignar las sectores a la data a enviar
        $this->respuestaData['calles'] = Calle::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'predioNew');
    }
    

    /**
     * Método guarda nuevo predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewPredio(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oPredioValidation = new PredioValidation();
        $validation = $oPredioValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoPredio'] = $postData;
        } else {
            
            // Obteniendo calle y cliente vinculados al predio
            $calle = Calle::find($postData['calle']);
            $cliente = Cliente::where('CLI_DOCUMENTO', '=', $postData['cliente'])->first();
            
            // Verificar existencia de calle
            if(!is_null($calle)){
                // Verificar existencia de cliente
                if(!is_null($cliente)){
                    
                    try{
                        $oPredio = new Predio();
                        $oPredio->PRE_DIRECCION = $postData['direccion'];
                        $oPredio->CAL_CODIGO = $calle->CAL_CODIGO;
                        $oPredio->CLI_CODIGO = $cliente->CLI_CODIGO;
                        
                        // Valores opcionales
                        $validData = $validation->getValidData();
                        if($validData['habitada'] != -1){$oPredio->PRE_HABITADA = $validData['habitada'];}
                        if($validData['materialConst'] != -1){$oPredio->PRE_MAT_CONSTRUCCION = $postData['materialConst'];}
                        if($validData['pisos'] != -1){$oPredio->PRE_PISOS = $postData['pisos'];}
                        if($validData['familias'] != -1){$oPredio->PRE_FAMILIAS = $postData['familias'];}
                        if($validData['habitantes'] != -1){$oPredio->PRE_HABITANTES = $postData['habitantes'];}
                        if($validData['pozoTabular'] != -1){$oPredio->PRE_POZO_TABULAR = $postData['pozoTabular'];}
                        if($validData['piscina'] != -1){$oPredio->PRE_PISCINA = $postData['piscina'];}
                        
                        $oPredio->save();
                        
                        // Redireccionamos a la vista detalle de predio
                        return new RedirectResponse('/predio/detalle/'.$oPredio->PRE_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Predio no resgistrado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el predio'];
                    }
                }else{
                    
                    // Controlando error por no encontrar cliente
                    $this->respuestaCodigo = 404;
                    $this->respuestaEstado = 'recurso no encontrado';
                    $this->respuestaEstadoDetalle = ['No se encontro el cliente solicitado'];
                    $this->respuestaData['formNuevoPredio'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar sector
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro la calle solicitada'];
                $this->respuestaData['formNuevoPredio'] = $postData;
            }
                
            
            
        }
        
        // Asignar las sectores a la data a enviar
        $this->respuestaData['calles'] = Calle::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'predioNew');
    }
    
    /**
     * Método muestra lista de predios
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListPredios(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Predio::count();
        $pagination = Predio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['predios'] = Predio::offset($pagination['paginaOffset'])
                                                        ->limit($pagination['paginaLimit'])
                                                        ->orderBy('PRE_CODIGO', 'desc')
                                                        ->get()->toArray();
        }else{
            $this->respuestaData['predios'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'predioList');
    }
    
    
    /**
     * Método muestra lista de predios según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListPredios(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar predios
        $oPredio = new Predio();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oPredio = $oPredio->where('PRE_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterDireccion']) && $queryData['filterDireccion'] != ''){
                $oPredio = $oPredio->where('PRE_DIRECCION', 'LIKE', $queryData['filterDireccion'].'%');
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oPredio->count();
        $pagination = Predio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListPredios'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['predios'] = $oPredio->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('PRE_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['predios'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'predioList');
    }
    
    /**
     * Método muestra todos los datos de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailPredio(ServerRequest $request)
    {
        $codePredio = (int) $request->getAttribute('predioId');
        
        $predio = Predio::find($codePredio);
        
        if($predio != null){
            $this->respuestaData['predio'] = $predio->toArray();
            $this->respuestaData['calle'] = $predio->calle->toArray();
            $this->respuestaData['cliente'] = $predio->cliente->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el predio solicitado'];
            $this->respuestaData['predioId'] = $codePredio;
        }
        
        return $this->renderHTML($this->viewsPath.'predioDetail');
    }
    
    /**
     * Método muestra todos los datos de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\JsonResponse
     */
    public function getDetailPredioJson(ServerRequest $request)
    {
        $codePredio = (int) $request->getAttribute('predioId');
        
        $predio = Predio::find($codePredio);
        
        if($predio != null){
            $this->respuestaData['predio']['direccion'] = $predio->PRE_DIRECCION;
            $this->respuestaData['calle']['nombre'] = $predio->calle->CAL_NOMBRE;
            $cliente = $predio->cliente;
            $this->respuestaData['cliente']['documento'] = $cliente->CLI_DOCUMENTO;
            $this->respuestaData['cliente']['nombre'] = $cliente->CLI_NOMBRES;
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el predio solicitado'];
            $this->respuestaData['predioId'] = $codePredio;
        }
        
        return $this->responseJSON();
    }
    
    /**
     * Método muestra la vista para editar datos de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditPredio(ServerRequest $request) {
        
        $codePredio = (int) $request->getAttribute('predioId');
        
        $predio = Predio::find($codePredio);
        
        if($predio != null){
            $this->respuestaData['predio'] = $predio->toArray();
            $this->respuestaData['calle'] = $predio->calle->toArray();
            $this->respuestaData['cliente'] = $predio->cliente->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el predio solicitado'];
            $this->respuestaData['predioId'] = $codePredio;
        }
        
        // Asignar las sectores a la data a enviar
        $this->respuestaData['calles'] = Calle::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'predioEdit');
    }
    
    
    /**
     * Método para actualizar datos de predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updatePredioData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oPredioValidation = new PredioValidation();
        $validation = $oPredioValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarPredio'] = $postData;
        } else {
            
            // predio a editar
            $predio = Predio::find($postData['codigo']);
            
            if(!is_null($predio)){
                
                // Obteniendo calle y cliente vinculados al predio
                $calle = Calle::find($postData['calle']);
                $cliente = Cliente::where('CLI_DOCUMENTO', '=', $postData['cliente'])->first();
                
                // Verificar existencia de calle
                if(!is_null($calle)){
                    // Verificar existencia de cliente
                    if(!is_null($cliente)){
                        
                        try{
                            $predio->PRE_DIRECCION = $postData['direccion'];
                            $predio->CAL_CODIGO = $calle->CAL_CODIGO;
                            $predio->CLI_CODIGO = $cliente->CLI_CODIGO;
                            
                            if(isset($postData['habitada'])){
                                $predio->PRE_HABITADA = ($postData['habitada'] == -1) ? "": $postData['habitada'];}
                            if(isset($postData['materialConst'])){
                                $predio->PRE_MAT_CONSTRUCCION = ($postData['materialConst'] == -1) ? "": $postData['materialConst'];}
                                
                            if(isset($postData['pisos']) && $postData['pisos'] != -1){
                                $predio->PRE_PISOS = $postData['pisos'];}
                            if(isset($postData['familias']) && $postData['familias'] != -1){
                                $predio->PRE_FAMILIAS = $postData['familias'];}
                            if(isset($postData['habitantes']) && $postData['habitantes'] != -1){
                                $predio->PRE_HABITANTES = $postData['habitantes'];}
                                
                            if(isset($postData['pozoTabular'])){
                                $predio->PRE_POZO_TABULAR = ($postData['pozoTabular'] == -1) ? "": $postData['pozoTabular'];}
                            if(isset($postData['piscina'])){
                                $predio->PRE_PISCINA = ($postData['piscina'] == -1) ? "": $postData['piscina'];}
                            
                            $predio->save();
                            
                            // Redireccionamos a la vista detalle de sector
                            return new RedirectResponse('/predio/detalle/'.$predio->PRE_CODIGO);
                            
                        }catch(PDOException $e){
                            $this->loggerfile->debug('Predio no actualizado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de predio'];
                        }
                        
                    }else{
                        
                        // Controlando error por no encontrar cliente
                        $this->respuestaCodigo = 404;
                        $this->respuestaEstado = 'recurso no encontrado';
                        $this->respuestaEstadoDetalle = ['No se encontro el cliente solicitado'];
                        $this->respuestaData['formEditarPredio'] = $postData;
                    }
                }else{
                    
                    // Controlando error por no encontrar sector
                    $this->respuestaCodigo = 404;
                    $this->respuestaEstado = 'recurso no encontrado';
                    $this->respuestaEstadoDetalle = ['No se encontro la calle solicitada'];
                    $this->respuestaData['formEditarPredio'] = $postData;
                }
                    
            }else{
                
                // Controlando error por no encontrar predio a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el predio solicitado'];
                $this->respuestaData['formEditarPredio'] = $postData;
            }
            
        }
        
        // Asignar las sectores a la data a enviar
        $this->respuestaData['calles'] = Calle::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'predioEdit');
    }
    
    /**
     * Método elimina predio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deletePredio(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oPredioValidation = new PredioValidation();
        $validation = $oPredioValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarPredio'] = $postData;
        } else {
            
            // Predio a eliminar
            $predio = Predio::find($postData['codigo']);
            if(!is_null($predio)){
                
                // El predio solo se podra eliminar si no se incluyo en algun contrato
                $oContrato = Contrato::where('PRE_CODIGO', '=', $predio->PRE_CODIGO)->first();
                if (is_null($oContrato)) {
                    
                    // El predio solo se podra eliminar si no se incluyo en alguna cuota extraordinaria
                    $oCuotaExtraordinaria = CuotaExtraordinaria::where('PRE_CODIGO', '=', $predio->PRE_CODIGO)->first();
                    if (is_null($oCuotaExtraordinaria)) {
                        
                        try{
                            $predio->delete();
                            
                            // Redireccionamos a la vista lista de predios
                            return new RedirectResponse('/predio/lista');
                            
                        }catch(PDOException $e){
                            $this->loggerfile->debug('Predio no eliminado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el predio'];
                        }
                        
                    }else{
                        
                        // Controlando error porque el predio no debe eliminarse por estar en una cuota extraordinaria
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validación';
                        $this->respuestaEstadoDetalle = ['El predio no puede eliminarse ya que éste fue incluido en una cuota extraordinaria'];
                        $this->respuestaData['formEliminarPredio'] = $postData;
                        
                        $this->respuestaData['predio'] = $predio->toArray();
                        $this->respuestaData['calle'] = $predio->calle->toArray();
                        $this->respuestaData['cliente'] = $predio->cliente->toArray();
                    }
                    
                }else{
                    
                    // Controlando error porque el predio no debe eliminarse por estar en un contrato
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['El predio no puede eliminarse ya que éste fue incluido en un contrato'];
                    $this->respuestaData['formEliminarPredio'] = $postData;
                    
                    $this->respuestaData['predio'] = $predio->toArray();
                    $this->respuestaData['calle'] = $predio->calle->toArray();
                    $this->respuestaData['cliente'] = $predio->cliente->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar predio a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el predio solicitado'];
                $this->respuestaData['formEliminarPredio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'predioDetail');
    }
    
}
  