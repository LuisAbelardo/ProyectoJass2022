<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Models\Recibo;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Libraries\Session;
use App\Validation\FinanciamientoValidation;
use App\Models\Financiamiento;


class FinanciamientoController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/financiamiento/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    
    
    /**
     * Método verifica recibos vencidos para financiar
     * @return \Laminas\Diactoros\Response\JsonResponse
     */
    public function checkRboForFinanciamiento(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oFinanciamientoValidation = new FinanciamientoValidation();
        $validation = $oFinanciamientoValidation->verifyRulesRboForFinanciamiento($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formFilterListRecibos'] = $postData;
        } else {
            
            $rboParaFinanciar = [];
            $codigoContrato = "";
            
            // Verificar que los recibos a financiar cumplan con los requisitos
            $rboAllExist = true;        // Todos los recibos deben existir
            $rboAllExpired = true;      // Todos los recibos deben estar vencidos y no deben haber sido financiado antes
            $rboAllSameContract = true; // Todos los recibos deben pertenecer a un mismo contrato
            foreach ($postData['rboVencidos'] as $rboCodigo) {
                $rbo = Recibo::find($rboCodigo);
                if (!is_null($rbo)) {
                    if($rbo->RBO_ESTADO == 3 && $rbo->FTO_CODIGO == null){
                        if ($codigoContrato == "") {
                            $codigoContrato = $rbo->CTO_CODIGO;
                            $rboParaFinanciar[] = $rbo->RBO_CODIGO;
                        }else{
                            if ($rbo->CTO_CODIGO == $codigoContrato) {
                                $rboParaFinanciar[] = $rbo->RBO_CODIGO;
                            }else{
                                $rboAllSameContract = false;
                                break;
                            }
                        }
                    }else {
                        $rboAllExpired = false;
                        break;
                    }
                }else{
                    $rboAllExist = false;
                    break;
                }
            }
            
            
            // Validar condiciones para financiar
            if ($rboAllExist) {
                if ($rboAllExpired) {
                    if ($rboAllSameContract) {
                        if (!empty($rboParaFinanciar)) {
                            
                            Session::add("rboParaFinanciar", $rboParaFinanciar);
                            
                            $this->respuestaCodigo = 200;
                            $this->respuestaEstado = 'ok';
                            $this->respuestaEstadoDetalle = ['Sera redireccionado para empezar el tramite'];
                        }else{
                            $this->respuestaCodigo = 400;
                            $this->respuestaEstado = 'error de validación';
                            $this->respuestaEstadoDetalle = ['Debe seleccionar los recibos a financiar'];
                        }
                    }else{
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validación';
                        $msjDetalle = 'Los recibos a financiar deben pertenecer a un mismo contrato';
                        $this->respuestaEstadoDetalle = [$msjDetalle];
                    }
                }else{
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $msjDetalle = 'Los recibos a financiar deben estar vencidos y no deben haber sido financiados con anterioridad';
                    $this->respuestaEstadoDetalle = [$msjDetalle];
                }
            }else{
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se econtró algunos de los recibos solicitado'];
            }
        }
        
        return  $this->responseJSON();
    }
    

    /**
     * Método muestra formulario nuevo financiamiento
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewFinanciamiento(ServerRequest $request)
    {
        $rboParaFinanciar = Session::get("rboParaFinanciar");
        
        if (!is_null($rboParaFinanciar) && !empty($rboParaFinanciar)) {
            $recibos = Recibo::find($rboParaFinanciar);
            
            if(!$recibos->isEmpty()){
                $this->respuestaData['recibos'] = $recibos->toArray();
                $auxRbo = $recibos->first();
                $contrato = $auxRbo->contrato;
                $this->respuestaData['contrato'] = $contrato->toArray();
                $predio = $contrato->predio;
                $this->respuestaData['predio'] = $predio->toArray();
                $cliente = $predio->cliente;
                $this->respuestaData['cliente'] = $cliente->toArray();
            }else{
                // Redireccionamos a la vista lista de recibos
                return new RedirectResponse('/recibo/lista');
                
            }
        }else{
            // Redireccionamos a la vista lista de recibos
            return new RedirectResponse('/recibo/lista');
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoNew');
    }
    

    /**
     * Método guarda nuevo financiamiento de recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewFinanciamiento(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oFinanciamientoValidation = new FinanciamientoValidation();
        $validation = $oFinanciamientoValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoFinanciamiento'] = $postData;
        } else {
            
            // Obteniendo recibos a financiar
            $recibos = Session::get('rboParaFinanciar');
            
            if (!is_null($recibos) && !empty($recibos)) {
                
                try{
                    
                    $oFinanciamiento = new Financiamiento();
                    $successFinanciamiento = false;
                    
                    Capsule::transaction(function() use($oFinanciamiento, $postData, $recibos, &$successFinanciamiento){
                        
                        $rboParaFinanciar = [];
                        $rboMontoFinanciamiento = 0;
                        $codigoContrato = "";
                        $rboValidated = true;
                        
                        // Verificar Recibos a financiar
                        foreach ($recibos as $rboCodigo) {
                            $rbo = Recibo::find($rboCodigo);
                            // Todos los recibos deben existir
                            if (!is_null($rbo)) {
                                // Todos los recibos deben estar vencidos y no deben haber sido financiado antes
                                if($rbo->RBO_ESTADO == 3 && $rbo->FTO_CODIGO == null){
                                    $rboParaFinanciar[] = $rbo;
                                    $rboMontoFinanciamiento = $rboMontoFinanciamiento + $rbo->RBO_MNTO_TOTAL;
                                    $codigoContrato = $rbo->CTO_CODIGO;
                                }else {
                                    $rboValidated = false;
                                    
                                    $this->respuestaCodigo = 400;
                                    $this->respuestaEstado = 'error de validación';
                                    $msjDetalle = 'Los recibos a financiar deben estar vencidos y no deben haber sido financiados con anterioridad';
                                    $this->respuestaEstadoDetalle = [$msjDetalle];
                                    break;
                                }
                            }else{
                                $rboValidated = false;
                                
                                $this->respuestaCodigo = 404;
                                $this->respuestaEstado = 'recurso no encontrado';
                                $this->respuestaEstadoDetalle = ['Recibo solicitado no encontrado'];
                                $this->respuestaData['formNuevoFinanciamiento'] = $postData;
                                break;
                            }
                        }
                        
                        
                        // Si las condiciones de financiamiento se cumplen empieza el proceso 
                        if ($rboValidated) {
                            $oFinanciamiento->FTO_DEUDA = $rboMontoFinanciamiento;
                            $oFinanciamiento->FTO_CUOTA_INICIAL = $postData['cuotaInicial'];
                            $oFinanciamiento->FTO_NUM_CUOTAS = $postData['nroCuotas'];
                            $oFinanciamiento->FTO_MONTO_CUOTA = ($rboMontoFinanciamiento - $postData['cuotaInicial']) / $postData['nroCuotas'];
                            $oFinanciamiento->FTO_OBSERVACION = $postData['observacion'];
                            $oFinanciamiento->FTO_ESTADO = 1;
                            $oFinanciamiento->CTO_CODIGO = $codigoContrato;
                            $oFinanciamiento->save();
                            
                            // Agregar financiamiento a recibos
                            foreach ($rboParaFinanciar as $rboPF) {
                                $rboPF->FTO_CODIGO = $oFinanciamiento->FTO_CODIGO;
                                $rboPF->RBO_ESTADO = 4;
                                $rboPF->save();
                            }
                        }
                        
                        // confirmamos el exito de la operacion
                        $successFinanciamiento = true;
                        
                    });
                    
                    // Si el financiamiento se realizó con exito
                    if ($successFinanciamiento) {
                        
                        // Eliminamos los recibos financiados de la sesión
                        Session::unset("rboParaFinanciar");
                        // Redireccionamos a la vista detalle de financiamiento
                        return new RedirectResponse('/financiamiento/detalle/'.$oFinanciamiento->FTO_CODIGO);
                    }
                        
                }catch(PDOException $e){
                    $this->loggerfile->debug('Financiamiento no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el financiamiento'];
                }
                
            }else{
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['Debe selecionar los recibos a financiar'];
            }
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoNew');
    }
    
    
    
    /**
     * Método confirma financiamiento
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function confirmFinanciamiento(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oFinanciamientoValidation = new FinanciamientoValidation();
        $validation = $oFinanciamientoValidation->verifyRulesConfirm($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formConfirmarFinanciamiento'] = $postData;
        } else {
            
            // Financiamiento a confirmar
            $financiamiento = Financiamiento::find($postData['codigo']);
            if(!is_null($financiamiento)){
                
                if($financiamiento->FTO_ESTADO == 1){
                    
                    try {
                        
                        Capsule::transaction(function() use($financiamiento){
                            
                            // Actualizar estado de financiamiento a confirmado
                            $financiamiento->FTO_ESTADO = 2;
                            $financiamiento->save();
                            
                            // Crear cuotas de financiamiento
                            $pdo = Capsule::connection()->getPdo();
                            $stmt=$pdo->prepare('CALL sp_set_financiar_cuotas(?, @msj)');
                            $stmt->execute([$financiamiento->FTO_CODIGO]);
                            $codeRptaBD = Capsule::select("SELECT @msj AS RESPUESTA");
                            
                            if ($codeRptaBD[0]->RESPUESTA != 200) {
                                throw new \Exception("Cuotas no generadas");
                            }
                            
                        });
                        
                        // Redireccionamos a la vista detalle de financiamiento
                        return new RedirectResponse('/financiamiento/detalle/'.$financiamiento->FTO_CODIGO);
                        
                    } catch (PDOException $e) {
                        $this->loggerfile->debug('Financiamiento no confirmado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se confirmó el financiamiento'];
                    }
                    
                }else{
                    // Controlando error por que el financiamiento no puede ser confirmado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $msj = "El financiamiento no puede ser confirmado";
                    if ($financiamiento->FTO_ESTADO == 2) {
                        $msj = 'El financiamiento ya fue confirmado';
                    }elseif ($financiamiento->FTO_ESTADO == 3){
                        $msj = 'El financiamiento no se puede confirmar ya que fue anulado';
                    }
                    $this->respuestaEstadoDetalle = [$msj];
                    $this->respuestaData['formConfirmarFinanciamiento'] = $postData;
                    
                    // Asignar datos a enviar
                    $this->respuestaData['financiamiento'] = $financiamiento->toArray();
                    $this->respuestaData['cuotas'] = $financiamiento->cuotas->toArray();
                    $this->respuestaData['recibos'] = $financiamiento->recibos->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar financiamiento a confirmar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el financiamiento solicitado'];
                $this->respuestaData['formConfirmarFinanciamiento'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoDetail');
    }
    
    
    
    /**
     * Método muestra lista de financiamientos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListFinanciamientos(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Financiamiento::count();
        $pagination = Financiamiento::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['financiamientos'] = Financiamiento::offset($pagination['paginaOffset'])
                                                                        ->limit($pagination['paginaLimit'])
                                                                        ->orderBy('FTO_CODIGO', 'desc')
                                                                        ->get()->toArray();
        }else{
            $this->respuestaData['financiamientos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoList');
    }
    
    
    /**
     * Método muestra lista de financiamientos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListFinanciamientos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar financiamientos
        $oFinanciamiento = new Financiamiento();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oFinanciamiento = $oFinanciamiento->where('FTO_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterContrato']) && $queryData['filterContrato'] != ''){
            $oFinanciamiento = $oFinanciamiento->where('CTO_CODIGO', '=', $queryData['filterContrato']);
        }
        if(isset($queryData['filterFecha']) && $queryData['filterFecha'] != '' && validateDate($queryData['filterFecha'])){
            $oFinanciamiento = $oFinanciamiento->whereDate('FTO_CREATED', '=', $queryData['filterFecha']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $oFinanciamiento = $oFinanciamiento->where('FTO_ESTADO', '=', $queryData['filterEstado']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oFinanciamiento->count();
        $pagination = Financiamiento::paginate($cantidadRegistros, $paginaActual);
            
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListFiananciamientos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['financiamientos'] = $oFinanciamiento->offset($pagination['paginaOffset'])
                                                                        ->limit($pagination['paginaLimit'])
                                                                        ->orderBy('FTO_CODIGO', 'desc')
                                                                        ->get()->toArray();
        }else{
            $this->respuestaData['financiamientos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoList');
    }
    
    /**
     * Método muestra todos los datos de financiamiento
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailFinanciamiento(ServerRequest $request)
    {
        $codeFinanciamiento = (int) $request->getAttribute('financiamientoId');
        
        $financiamiento = Financiamiento::find($codeFinanciamiento);
        
        if($financiamiento != null){
            $this->respuestaData['financiamiento'] = $financiamiento->toArray();
            $this->respuestaData['cuotas'] = $financiamiento->cuotas->toArray();
            $this->respuestaData['recibos'] = $financiamiento->recibos->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el financiamiento solicitado'];
            $this->respuestaData['financiamientoId'] = $codeFinanciamiento;
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoDetail');
    }
    
    
    /**
     * Método anula financiamiento
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function AnnularFinanciamiento(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oFinanciamientoValidation = new FinanciamientoValidation();
        $validation = $oFinanciamientoValidation->veryRulesAnnular($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formAnularFinanciamiento'] = $postData;
        } else {
            
            // Financiamiento a anular
            $financiamiento = Financiamiento::find($postData['codigo']);
            if(!is_null($financiamiento)){
                
                if($financiamiento->FTO_ESTADO == 1){
                    
                    try {
                        
                        Capsule::transaction(function() use($financiamiento){
                            
                            // Actualizar estado de financiamiento a anulado
                            $financiamiento->FTO_ESTADO = 3;
                            $financiamiento->save();
                            
                            // Cambiar estado a los recibos
                            $recibos = $financiamiento->recibos;
                            foreach ($recibos as $recibo) {
                                $recibo->RBO_ESTADO = 3;
                                $recibo->FTO_CODIGO = NULL;
                                $recibo->save();
                            }
                            
                        }); 
                            
                        // Redireccionamos a la vista detalle de financiamiento
                        return new RedirectResponse('/financiamiento/detalle/'.$financiamiento->FTO_CODIGO);
                            
                    } catch (PDOException $e) {
                        $this->loggerfile->debug('Financiamiento no anulado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el financiamiento'];
                    }
                    
                }else{
                    // Controlando error por que el financiamiento no puede ser anulado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $msj = "El financiamiento no puede ser eliminado";
                    if ($financiamiento->FTO_ESTADO == 2) {
                        $msj = 'El financiamiento no puede ser anulado porque este ya fue confirmado';
                    }elseif ($financiamiento->FTO_ESTADO == 3){
                        $msj = 'El financiamiento ya fue anulado';
                    }
                    $this->respuestaEstadoDetalle = [$msj];
                    $this->respuestaData['formAnularFinanciamiento'] = $postData;
                    
                    // Asignar datos a enviar
                    $this->respuestaData['financiamiento'] = $financiamiento->toArray();
                    $this->respuestaData['cuotas'] = $financiamiento->cuotas->toArray();
                    $this->respuestaData['recibos'] = $financiamiento->recibos->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar financiamiento a anular
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el financiamiento solicitado'];
                $this->respuestaData['formAnularFinanciamiento'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'financiamientoDetail');
    }
    
}
  