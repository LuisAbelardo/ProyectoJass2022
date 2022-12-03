<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Models\Recibo;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\CuotaExtraordinaria;
use App\Validation\IngresoValidation;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Libraries\Session;
use App\Models\Ingreso;
use App\Models\Caja;
use App\Models\TipoUsuario;
use App\Models\User;
use App\Models\Igv;
use App\Models\FinancCuota;


class IngresoController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/ingreso/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario pagar recibo
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewPagoRecibo(ServerRequest $request)
    {
        $codeRecibo = (int) $request->getAttribute('reciboId');
        
        $recibo = Recibo::find($codeRecibo);
        
        if($recibo != null){
            $this->respuestaData['recibo'] = $recibo->toArray();
            $contrato = $recibo->contrato;
            $predio = $contrato->predio;
            $this->respuestaData['predio'] = $predio->toArray();
            $cliente = $predio->cliente;
            $this->respuestaData['cliente'] = $cliente->toArray();
            
            // Asignamos las cajas a la data a enviar
            $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
            $cajas = $tipoUsuario->cajas;
            $this->respuestaData['cajas'] = $cajas->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el recibo solicitado'];
            $this->respuestaData['reciboId'] = $codeRecibo;
        }
        
        return $this->renderHTML($this->viewsPath.'ingresoNewPagoRecibo');
    }
    
    /**
     * Método muestra formulario pagar cuota extraordinaria
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewPagoCuotaExtraordinaria(ServerRequest $request)
    {
        $codeCuotaExtra = (int) $request->getAttribute('cuotaExtraId');
        
        $cuotaExtra = CuotaExtraordinaria::find($codeCuotaExtra);
        
        if($cuotaExtra != null){
            $this->respuestaData['cuotaExtra'] = $cuotaExtra->toArray();
            $contrato = $cuotaExtra->contrato;
            $predio = $contrato->predio;
            $this->respuestaData['predio'] = $predio->toArray();
            $cliente = $predio->cliente;
            $this->respuestaData['cliente'] = $cliente->toArray();
            
            // Asignamos las cajas a la data a enviar
            $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
            $cajas = $tipoUsuario->cajas;
            $this->respuestaData['cajas'] = $cajas->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el recibo solicitado'];
            $this->respuestaData['cuotaExtraId'] = $codeCuotaExtra;
        }
        
        return $this->renderHTML($this->viewsPath.'ingresoNewPagoCuotaExtraordinaria');
    }
    
    
    /**
     * Método muestra formulario para registrar nuevo ingreso por motivos diversos
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewIngresoOtros()
    {
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoNewOtros');
    }
    

    /**
     * Método guarda nuevo ingreso por pago de recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewIngresoPagoRecibo(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oIngresoValidation = new IngresoValidation();
        $validation = $oIngresoValidation->verifyRulesNewPagoRecibo($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoPagoRecibo'] = $postData;
        } else {
            
            $recibo = Recibo::find($postData['reciboCodigo']);
            
            if(!is_null($recibo)){
                
                if($postData['montoRecibido'] >= $recibo->RBO_MNTO_TOTAL){
                    
                    try{
                        
                        $oIngreso = new Ingreso();
                        $oIgv = Igv::find(1);
                        
                        Capsule::transaction(function() use($oIngreso, $recibo, $postData, $oIgv){
                            
                            $oIngreso->IGR_TIPO = "RBO";
                            $oIngreso->IGR_CANTIDAD = $recibo->RBO_MNTO_TOTAL;
                            $oIngreso->IGR_MNTO_RECIBIDO = $postData['montoRecibido'];
                            $oIngreso->IGR_TIPO_COMPROBANTE = $postData['comprobanteTipo'];
                            $oIngreso->IGR_DESCRIPCION = "Pago de recibo ref: {$recibo->RBO_CODIGO}";
                            $oIngreso->IGR_ESTADO = 1;
                            $oIngreso->CAJ_CODIGO = $postData['caja'];
                            $oIngreso->USU_CODIGO = Session::getUserValue('id');
                            
                            // Establecer igv
                            if ($oIgv->IGV_PORCENTAJE == 0) {
                                $oIngreso->IGR_IGV = 0;
                            }else{
                                $oIngreso->IGR_IGV = $recibo->RBO_MNTO_CONSUMO * ($oIgv->IGV_PORCENTAJE / 100);
                            }
                            
                            // Si el comprobante es diferente de ticket
                            if ($postData['comprobanteTipo'] != 1) {
                                $oIngreso->IGR_COD_COMPROBANTE = $postData['comprobanteNro'];
                            }
                            $oIngreso->save();
                            
                            // Si el comprobante es ticket
                            if ($postData['comprobanteTipo'] == 1) {
                                $oIngreso->IGR_COD_COMPROBANTE = "ND-{$oIngreso->IGR_CODIGO}";
                                $oIngreso->save();
                            }
                            
                            // Actualizar saldo de caja
                            $caja = Caja::find($postData['caja']);
                            if(!is_null($caja) && TipoUsuarioCajaController::verifyUserAccessToCaja($caja->CAJ_CODIGO)){
                                $caja->CAJ_SALDO = $caja->CAJ_SALDO + $oIngreso->IGR_CANTIDAD;
                                $caja->save();
                            }else {
                                throw new PDOException('Caja no encontrada o tiene permisos insuficientes');
                            }
                            
                            
                            // Cambiando estado de recibo a pagado
                            $recibo->RBO_ESTADO = 2;
                            $recibo->IGR_CODIGO = $oIngreso->IGR_CODIGO;
                            $recibo->save();
                            
                            
                            // Cambiando estado de cuota de financiamiento si existiera a "pagado"
                            if(!is_null($recibo->FCU_CODIGO)){
                                $financCuota = FinancCuota::find($recibo->FCU_CODIGO);
                                if (!is_null($financCuota)) {
                                    $financCuota->FCU_ESTADO = 3;
                                    $financCuota->save();
                                }else{
                                    throw new PDOException('Cuota de financiamiento no encontrado');
                                }
                            }
                            
                        });
                            
                        // Redireccionamos a la vista detalle de ingreso
                        return new RedirectResponse('/ingreso/detalle/'.$oIngreso->IGR_CODIGO);
                            
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Ingreso no resgistrado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el ingreso'];
                    }
                    
                }else{
                    // Controlando errores de validación porque el monto recibido es menor al monto a registrar
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['El monto recibido es menor al monto total del recibo'];;
                    $this->respuestaData['formNuevoPagoRecibo'] = $postData;
                }
                
            }else{
                
                // Controlando error por no encontrar recibo
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró el recibo solicitado'];
                $this->respuestaData['formNuevoPagoRecibo'] = $postData;
            }
            
        }
        
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoNewPagoRecibo');
    }
    
    
    
    /**
     * Método guarda nuevo ingreso por pago de cuota extraordinaria
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewIngresoPagoCuotaExtraordinaria(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oIngresoValidation = new IngresoValidation();
        $validation = $oIngresoValidation->verifyRulesNewPagoCuotaExtraordinaria($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoPagoCuotaExtra'] = $postData;
        } else {
            
            $cuotaExtra = CuotaExtraordinaria::find($postData['cuotaExtraCodigo']);
            
            if(!is_null($cuotaExtra)){
                
                if($postData['montoRecibido'] >= $cuotaExtra->CUE_MNTO_CUOTA){
                    
                    try{
                        
                        $oIngreso = new Ingreso();
                        
                        Capsule::transaction(function() use($oIngreso, $cuotaExtra, $postData){
                            
                            $oIngreso->IGR_TIPO = "CUE";
                            $oIngreso->IGR_CANTIDAD = $cuotaExtra->CUE_MNTO_CUOTA;
                            $oIngreso->IGR_MNTO_RECIBIDO = $postData['montoRecibido'];
                            $oIngreso->IGR_TIPO_COMPROBANTE = $postData['comprobanteTipo'];
                            $oIngreso->IGR_DESCRIPCION = "Pago de cuota extraordinaria ref: {$cuotaExtra->CUE_CODIGO}";
                            $oIngreso->IGR_ESTADO = 1;
                            $oIngreso->CAJ_CODIGO = $postData['caja'];
                            $oIngreso->USU_CODIGO = Session::getUserValue('id');
                            $oIngreso->IGR_IGV = 0;
                            
                            // Si el comprobante es diferente de ticket
                            if ($postData['comprobanteTipo'] != 1) {
                                $oIngreso->IGR_COD_COMPROBANTE = $postData['comprobanteNro'];
                            }
                            $oIngreso->save();
                            
                            // Si el comprobante es ticket
                            if ($postData['comprobanteTipo'] == 1) {
                                $oIngreso->IGR_COD_COMPROBANTE = "ND-{$oIngreso->IGR_CODIGO}";
                                $oIngreso->save();
                            }
                            
                            // Actualizar saldo de caja
                            $caja = Caja::find($postData['caja']);
                            if(!is_null($caja) && TipoUsuarioCajaController::verifyUserAccessToCaja($caja->CAJ_CODIGO)){
                                $caja->CAJ_SALDO = $caja->CAJ_SALDO + $oIngreso->IGR_CANTIDAD;
                                $caja->save();
                            }else {
                                throw new PDOException('Caja no encontrada o tiene permisos insuficientes');
                            }
                            
                            
                            // Cambiando estado de cuota extraordinaria a pagado
                            $cuotaExtra->CUE_ESTADO = 2;
                            $cuotaExtra->IGR_CODIGO = $oIngreso->IGR_CODIGO;
                            $cuotaExtra->save();
                            
                            
                        });
                            
                        // Redireccionamos a la vista detalle de ingreso
                        return new RedirectResponse('/ingreso/detalle/'.$oIngreso->IGR_CODIGO);
                            
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Ingreso no resgistrado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el ingreso'];
                    }
                    
                }else{
                    // Controlando error de validación porque el monto recibido es menor al monto a registrar
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['El monto recibido es menor al monto de cuota'];;
                    $this->respuestaData['formNuevoPagoCuotaExtra'] = $postData;
                }
                
            }else{
                
                // Controlando error por no encontrar cuota extraordinaria
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró la cuota extraordinaria solicitada'];
                $this->respuestaData['formNuevoPagoCuotaExtra'] = $postData;
            }
            
        }
        
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoNewPagoCuotaExtraordinaria');
    }
    
    
    
    /**
     * Método guarda nuevo ingreso por concepto diversos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewIngresoOtros(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oIngresoValidation = new IngresoValidation();
        $validation = $oIngresoValidation->verifyRulesNewIngresoOtros($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoIngreso'] = $postData;
        } else {
                
            if($postData['montoRecibido'] >= $postData['montoTotal']){
                    
                try{
                    
                    $oIngreso = new Ingreso();
                    
                    Capsule::transaction(function() use($oIngreso, $postData){
                        
                        $oIngreso->IGR_TIPO = "OTRO";
                        $oIngreso->IGR_CANTIDAD = $postData['montoTotal'];
                        $oIngreso->IGR_IGV = 0;
                        $oIngreso->IGR_MNTO_RECIBIDO = $postData['montoRecibido'];
                        $oIngreso->IGR_TIPO_COMPROBANTE = $postData['comprobanteTipo'];
                        $oIngreso->IGR_DESCRIPCION = $postData['descripcion'];
                        $oIngreso->IGR_ESTADO = 1;
                        $oIngreso->CAJ_CODIGO = $postData['caja'];
                        $oIngreso->USU_CODIGO = Session::getUserValue('id');
                        
                        // Si el comprobante es diferente de ticket
                        if ($postData['comprobanteTipo'] != 1) {
                            $oIngreso->IGR_COD_COMPROBANTE = $postData['comprobanteNro'];
                        }
                        $oIngreso->save();
                        
                        // Si el comprobante es ticket
                        if ($postData['comprobanteTipo'] == 1) {
                            $oIngreso->IGR_COD_COMPROBANTE = "ND-{$oIngreso->IGR_CODIGO}";
                            $oIngreso->save();
                        }
                        
                        // Actualizar saldo de caja
                        $caja = Caja::find($postData['caja']);
                        if(!is_null($caja) && TipoUsuarioCajaController::verifyUserAccessToCaja($caja->CAJ_CODIGO)){
                            $caja->CAJ_SALDO = $caja->CAJ_SALDO + $oIngreso->IGR_CANTIDAD;
                            $caja->save();
                        }else {
                            throw new PDOException('Caja no encontrada o tiene permisos insuficientes');
                        }
                        
                    });
                        
                    // Redireccionamos a la vista detalle de ingreso
                    return new RedirectResponse('/ingreso/detalle/'.$oIngreso->IGR_CODIGO);
                        
                }catch(PDOException $e){
                    $this->loggerfile->debug('Ingreso no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el ingreso'];
                }
                    
            }else{
                // Controlando error de validación porque el monto recibido es menor al monto a registrar
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validacion';
                $this->respuestaEstadoDetalle = ['El monto recibido es menor al monto a registrar'];;
                $this->respuestaData['formNuevoIngreso'] = $postData;
            }
                
        }
        
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoNewOtros');
    }
    
    
    
    /**
     * Método muestra lista de ingresos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListIngresos(ServerRequest $request)
    {
        $ingreso = Ingreso::join('CAJA', 'INGRESO.CAJ_CODIGO', '=', 'CAJA.CAJ_CODIGO')
                            ->select('INGRESO.*', 'CAJA.CAJ_NOMBRE');
        
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) $ingreso->count();
        $pagination = Ingreso::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['ingresos'] = $ingreso->offset($pagination['paginaOffset'])
                                                        ->limit($pagination['paginaLimit'])
                                                        ->orderBy('IGR_CODIGO', 'desc')
                                                        ->get()->toArray();
        }else{
            $this->respuestaData['ingresos'] = [];
        }
        
        // Asignar las cajas a la data a enviar
        $cajas = Caja::all();
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoList');
    }
    
    
    /**
     * Método muestra lista de ingresos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListIngresos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar ingresos
        $ingreso = Ingreso::join('CAJA', 'INGRESO.CAJ_CODIGO', '=', 'CAJA.CAJ_CODIGO')
                            ->select('INGRESO.*', 'CAJA.CAJ_NOMBRE');
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $ingreso = $ingreso->where('IGR_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterFecha']) && $queryData['filterFecha'] != '' && validateDate($queryData['filterFecha'])){
            $ingreso = $ingreso->whereDate('IGR_CREATED', '=', $queryData['filterFecha']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $ingreso = $ingreso->where('IGR_ESTADO', '=', $queryData['filterEstado']);
        }
        if(isset($queryData['filterCaja']) && $queryData['filterCaja'] != '' && $queryData['filterCaja'] != -1){
            $ingreso = $ingreso->where('CAJA.CAJ_CODIGO', '=', $queryData['filterCaja']);
        }
        if(isset($queryData['filterComprobanteTipo']) && $queryData['filterComprobanteTipo'] != '' && $queryData['filterComprobanteTipo'] != -1){
            $ingreso = $ingreso->where('IGR_TIPO_COMPROBANTE', '=', $queryData['filterComprobanteTipo']);
        }
        if(isset($queryData['filterComprobanteNro']) && $queryData['filterComprobanteNro'] != ''){
            $ingreso = $ingreso->where('IGR_COD_COMPROBANTE', '=', $queryData['filterComprobanteNro']);
        }
        if(isset($queryData['filterUsuario']) && $queryData['filterUsuario'] != ''){
            $ingreso = $ingreso->where('USU_CODIGO', '=', $queryData['filterUsuario']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $ingreso->count();
        $pagination = Ingreso::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListIngresos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['ingresos'] = $ingreso->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('IGR_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['ingresos'] = [];
        }
        
        // Asignar las cajas a la data a enviar
        $cajas = Caja::all();
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'ingresoList');
    }
    
    /**
     * Método muestra todos los datos de ingreso
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailIngreso(ServerRequest $request)
    {
        $codeIngreso = (int) $request->getAttribute('ingresoId');
        
        $ingreso = Ingreso::find($codeIngreso);
        
        if($ingreso != null){
            $this->respuestaData['ingreso'] = $ingreso->toArray();
            $this->respuestaData['caja'] = $ingreso->caja->toArray();
            $usuario = User::find($ingreso->USU_CODIGO);
            $this->respuestaData['usuario'] = $usuario->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtró el ingreso solicitado'];
            $this->respuestaData['ingresoId'] = $codeIngreso;
        }
        
        return $this->renderHTML($this->viewsPath.'ingresoDetail');
    }
    
    
    /**
     * Método anula ingreso
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function AnnularIngreso(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oIngresoValidation = new IngresoValidation();
        $validation = $oIngresoValidation->veryRulesAnnular($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formAnularIngreso'] = $postData;
        } else {
            
            // Ingreso a anular
            $ingreso = Ingreso::find($postData['codigo']);
            if(!is_null($ingreso)){
                
                switch ($ingreso->IGR_TIPO) {
                    case 'RBO':
                        
                        $recibo = Recibo::where('IGR_CODIGO', '=', $ingreso->IGR_CODIGO)->first();
                        if(!is_null($recibo)){
                            
                            try {
                                
                                Capsule::transaction(function() use($ingreso, $recibo){
                                    
                                    // Actualizar estado de recibo a pendente
                                    $recibo->RBO_ESTADO = 1;
                                    $recibo->save();
                                    
                                    // Anular ingreso
                                    $ingreso->IGR_ESTADO = 0;
                                    $ingreso->save();
                                    
                                    // Descontamos el saldo de la caja
                                    $caja = $ingreso->caja;
                                    $caja->CAJ_SALDO = $caja->CAJ_SALDO - $ingreso->IGR_CANTIDAD;
                                    $caja->save();
                                    
                                    // Cambiando estado de cuota de financiamiento si existiera a "facturado"
                                    if(!is_null($recibo->FCU_CODIGO)){
                                        $financCuota = FinancCuota::find($recibo->FCU_CODIGO);
                                        if (!is_null($financCuota)) {
                                            $financCuota->FCU_ESTADO = 2;
                                            $financCuota->save();
                                        }else{
                                            throw new PDOException('Cuota de financiamiento no encontrado');
                                        }
                                    }
                                    
                                });
                                    
                                // Redireccionamos a la vista detalle de ingreso
                                return new RedirectResponse('/ingreso/detalle/'.$ingreso->IGR_CODIGO);
                                
                            } catch (PDOException $e) {
                                $this->loggerfile->debug('Ingreso no anulado. Mensaje:'.$e->getMessage());
                                $this->respuestaCodigo = 500;
                                $this->respuestaEstado = 'error de servidor';
                                $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el ingreso'];
                            }
                            
                        }else{
                            // Controlando error por no encontrar el recibo pagado
                            $this->respuestaCodigo = 404;
                            $this->respuestaEstado = 'recurso no encontrado';
                            $this->respuestaEstadoDetalle = ['No se encontro el recibo asociado al pago'];
                            $this->respuestaData['formAnularIngreso'] = $postData;
                            
                            // Asignar datos a enviar
                            $this->respuestaData['ingreso'] = $ingreso->toArray();
                            $this->respuestaData['caja'] = $ingreso->caja->toArray();
                            $usuario = User::find($ingreso->USU_CODIGO);
                            $this->respuestaData['usuario'] = $usuario->toArray();
                        }
                        
                        break;
                    
                    case 'CUE':
                        
                        $cuotaExtraordinaria = CuotaExtraordinaria::where('IGR_CODIGO', '=', $ingreso->IGR_CODIGO)->first();
                        if(!is_null($cuotaExtraordinaria)){
                            
                            try {
                                
                                Capsule::transaction(function() use($ingreso, $cuotaExtraordinaria){
                                    
                                    // Actualizar estado de cuota extraordinaria a pendente
                                    $cuotaExtraordinaria->CUE_ESTADO = 1;
                                    $cuotaExtraordinaria->save();
                                    
                                    // Anular ingreso
                                    $ingreso->IGR_ESTADO = 0;
                                    $ingreso->save();
                                    
                                    // Descontamos el saldo de la caja
                                    $caja = $ingreso->caja;
                                    $caja->CAJ_SALDO = $caja->CAJ_SALDO - $ingreso->IGR_CANTIDAD;
                                    $caja->save();
                                    
                                });
                                    
                                // Redireccionamos a la vista detalle de ingreso
                                return new RedirectResponse('/ingreso/detalle/'.$ingreso->IGR_CODIGO);
                                
                            } catch (PDOException $e) {
                                $this->loggerfile->debug('Ingreso no anulado. Mensaje:'.$e->getMessage());
                                $this->respuestaCodigo = 500;
                                $this->respuestaEstado = 'error de servidor';
                                $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el ingreso'];
                            }
                                
                        }else{
                            // Controlando error por no encontrar la cuota extraordinaria pagada
                            $this->respuestaCodigo = 404;
                            $this->respuestaEstado = 'recurso no encontrado';
                            $this->respuestaEstadoDetalle = ['No se encontro la cuota extraordinaria asociado al pago'];
                            $this->respuestaData['formAnularIngreso'] = $postData;
                            
                            // Asignar datos a enviar
                            $this->respuestaData['ingreso'] = $ingreso->toArray();
                            $this->respuestaData['caja'] = $ingreso->caja->toArray();
                            $usuario = User::find($ingreso->USU_CODIGO);
                            $this->respuestaData['usuario'] = $usuario->toArray();
                        }
                        
                        break;
                        
                    case 'TRANSF':
                        
                        // Controlando error porque las transferencias no pueden ser anuladas
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validacion';
                        $this->respuestaEstadoDetalle = ['El ingreso no puede ser anulado ya que forma parte de una transferencia'];
                        $this->respuestaData['formAnularIngreso'] = $postData;
                        
                        // Asignar datos a enviar
                        $this->respuestaData['ingreso'] = $ingreso->toArray();
                        $this->respuestaData['caja'] = $ingreso->caja->toArray();
                        $usuario = User::find($ingreso->USU_CODIGO);
                        $this->respuestaData['usuario'] = $usuario->toArray();
                        
                        break;
                        
                    default:
                        
                        try {
                            
                            Capsule::transaction(function() use($ingreso){
                                
                                // Anular ingreso
                                $ingreso->IGR_ESTADO = 0;
                                $ingreso->save();
                                
                                // Descontamos el saldo de la caja
                                $caja = $ingreso->caja;
                                $caja->CAJ_SALDO = $caja->CAJ_SALDO - $ingreso->IGR_CANTIDAD;
                                $caja->save();
                                
                            });
                                
                            // Redireccionamos a la vista detalle de ingreso
                            return new RedirectResponse('/ingreso/detalle/'.$ingreso->IGR_CODIGO);
                            
                        } catch (PDOException $e) {
                            $this->loggerfile->debug('Ingreso no anulado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el ingreso'];
                        }
                        
                        break;
                }
                
            }else{
                
                // Controlando error por no encontrar ingreso a anular
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró el ingreso solicitado'];
                $this->respuestaData['formAnularIngreso'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'ingresoDetail');
    }
    
}
  