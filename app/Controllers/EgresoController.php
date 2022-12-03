<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Libraries\Session;
use App\Models\Caja;
use App\Models\Egreso;
use App\Validation\EgresoValidation;
use App\Models\TipoUsuario;
use App\Models\User;


class EgresoController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/egreso/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    
    /**
     * Método muestra formulario para registrar nuevo egreso
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewEgreso()
    {
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'egresoNew');
    }
    
    
    
    /**
     * Método guarda nuevo egreso
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewEgreso(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oEgresoValidation = new EgresoValidation();
        $validation = $oEgresoValidation->verifyRulesNew($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoEgreso'] = $postData;
        } else {

            $caja = Caja::find($postData['caja']);
            
            // Verificamos existencia de caja y permiso del usuario para interactuar con ésta.
            if(!is_null($caja) && TipoUsuarioCajaController::verifyUserAccessToCaja($caja->CAJ_CODIGO)){
                
                try{
                    
                    $oEgreso = new Egreso();
                    $operacionRealizada = false;
                    
                    Capsule::transaction(function() use($caja, $oEgreso, $postData, &$operacionRealizada){
                        
                        // Efectuamos el retiro solo si el saldo es suficiente
                        if ($caja->CAJ_SALDO >= $postData['monto']) {
                            $oEgreso->EGR_TIPO = "OTRO";
                            $oEgreso->EGR_CANTIDAD = $postData['monto'];
                            $oEgreso->EGR_TIPO_COMPROBANTE = $postData['comprobanteTipo'];
                            $oEgreso->EGR_DESCRIPCION = $postData['descripcion'];
                            $oEgreso->EGR_ESTADO = 1;
                            $oEgreso->CAJ_CODIGO = $postData['caja'];
                            $oEgreso->USU_CODIGO = Session::getUserValue('id');
                            
                            // Si el comprobante es diferente de "sin comprobante" agregamos el número de recibo
                            if ($postData['comprobanteTipo'] != 4) {
                                $oEgreso->EGR_COD_COMPROBANTE = $postData['comprobanteNro'];
                            }
                            $oEgreso->save();
                            
                            // Actualizar saldo de caja
                            $caja->CAJ_SALDO = $caja->CAJ_SALDO - $oEgreso->EGR_CANTIDAD;
                            $caja->save();
                            
                            // confirmamos el exito de la operacion
                            $operacionRealizada = true;
                            
                        }else{
                            $this->respuestaCodigo = 400;
                            $this->respuestaEstado = 'error de validacion';
                            $this->respuestaEstadoDetalle = ['Saldo insuficiente para efectuar el retiro'];
                            $this->respuestaData['formNuevoEgreso'] = $postData;
                        }
                        
                    });
                        
                    // Si efectuamos la operación redireccionamos al detalle de retiro
                    if ($operacionRealizada) {
                        // Redireccionamos a la vista detalle de egreso
                        return new RedirectResponse('/egreso/detalle/'.$oEgreso->EGR_CODIGO);
                    }
                        
                        
                }catch(PDOException $e){
                    $this->loggerfile->debug('Egreso no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['No se guardó el egreso. Ocurrio un error inesperado'];
                }
                
            }else {
                // Controlando errores de validación por no encontrar caja
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['No se encontró la caja solicitada o tiene permisos insuficientes'];
                $this->respuestaData['formNuevoEgreso'] = $postData;
            }
                
        }
        
        // Asignamos las cajas a la data a enviar
        $tipoUsuario = TipoUsuario::find(Session::getUserValue('rol_id'));
        $cajas = $tipoUsuario->cajas;
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'egresoNew');
    }
    
    
    
    /**
     * Método muestra lista de egresos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListEgresos(ServerRequest $request)
    {
        $egreso = Egreso::join('CAJA', 'EGRESO.CAJ_CODIGO', '=', 'CAJA.CAJ_CODIGO')
                            ->select('EGRESO.*', 'CAJA.CAJ_NOMBRE');
        
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) $egreso->count();
        $pagination = Egreso::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['egresos'] = $egreso->offset($pagination['paginaOffset'])
                                                        ->limit($pagination['paginaLimit'])
                                                        ->orderBy('EGR_CODIGO', 'desc')
                                                        ->get()->toArray();
        }else{
            $this->respuestaData['egresos'] = [];
        }
        
        // Asignar las cajas a la data a enviar
        $cajas = Caja::all();
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'egresoList');
    }
    
    
    /**
     * Método muestra lista de egresos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListEgresos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar egresos
        $egreso = Egreso::join('CAJA', 'EGRESO.CAJ_CODIGO', '=', 'CAJA.CAJ_CODIGO')
                            ->select('EGRESO.*', 'CAJA.CAJ_NOMBRE');
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $egreso = $egreso->where('EGR_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterFecha']) && $queryData['filterFecha'] != '' && validateDate($queryData['filterFecha'])){
            $egreso = $egreso->whereDate('EGR_CREATED', '=', $queryData['filterFecha']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $egreso = $egreso->where('EGR_ESTADO', '=', $queryData['filterEstado']);
        }
        if(isset($queryData['filterCaja']) && $queryData['filterCaja'] != '' && $queryData['filterCaja'] != -1){
            $egreso = $egreso->where('CAJA.CAJ_CODIGO', '=', $queryData['filterCaja']);
        }
        if(isset($queryData['filterComprobanteTipo']) && $queryData['filterComprobanteTipo'] != '' && $queryData['filterComprobanteTipo'] != -1){
            $egreso = $egreso->where('EGR_TIPO_COMPROBANTE', '=', $queryData['filterComprobanteTipo']);
        }
        if(isset($queryData['filterComprobanteNro']) && $queryData['filterComprobanteNro'] != ''){
            $egreso = $egreso->where('EGR_COD_COMPROBANTE', '=', $queryData['filterComprobanteNro']);
        }
        if(isset($queryData['filterUsuario']) && $queryData['filterUsuario'] != ''){
            $egreso = $egreso->where('USU_CODIGO', '=', $queryData['filterUsuario']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $egreso->count();
        $pagination = Egreso::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListEgresos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['egresos'] = $egreso->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('EGR_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['egresos'] = [];
        }
        
        // Asignar las cajas a la data a enviar
        $cajas = Caja::all();
        $this->respuestaData['cajas'] = $cajas->toArray();
        
        return $this->renderHTML($this->viewsPath.'egresoList');
    }
    
    
    /**
     * Método muestra todos los datos de egreso
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailEgreso(ServerRequest $request)
    {
        $codeEgreso = (int) $request->getAttribute('egresoId');
        
        $egreso = Egreso::find($codeEgreso);
        
        if($egreso != null){
            $this->respuestaData['egreso'] = $egreso->toArray();
            $this->respuestaData['caja'] = $egreso->caja->toArray();
            $usuario = User::find($egreso->USU_CODIGO);
            $this->respuestaData['usuario'] = $usuario->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtró el egreso solicitado'];
            $this->respuestaData['egresoId'] = $codeEgreso;
        }
        
        return $this->renderHTML($this->viewsPath.'egresoDetail');
    }
    
    
    /**
     * Método anula egreso
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function AnnularEgreso(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oEgresoValidation = new EgresoValidation();
        $validation = $oEgresoValidation->veryRulesAnnular($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formAnularEgreso'] = $postData;
        } else {
            
            // Egreso a anular
            $egreso = Egreso::find($postData['codigo']);
            if(!is_null($egreso)){
                
                switch ($egreso->EGR_TIPO) {
                    case "TRANSF":
                    
                        // Controlando error por no encontrar egreso a anular
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validacion';
                        $this->respuestaEstadoDetalle = ['El egreso no puede ser anulado ya que forma parte de una transferencia'];
                        $this->respuestaData['formAnularEgreso'] = $postData;
                        
                        $this->respuestaData['egreso'] = $egreso->toArray();
                        $this->respuestaData['caja'] = $egreso->caja->toArray();
                        $usuario = User::find($egreso->USU_CODIGO);
                        $this->respuestaData['usuario'] = $usuario->toArray();
                    break;
                    
                    default:
                        
                        try {
                            
                            Capsule::transaction(function() use($egreso){
                                
                                // Anular egreso
                                $egreso->EGR_ESTADO = 0;
                                $egreso->save();
                                
                                // Descontamos el saldo de la caja
                                $caja = $egreso->caja;
                                $caja->CAJ_SALDO = $caja->CAJ_SALDO + $egreso->EGR_CANTIDAD;
                                $caja->save();
                                
                            });
                                
                                // Redireccionamos a la vista detalle de egreso
                                return new RedirectResponse('/egreso/detalle/'.$egreso->EGR_CODIGO);
                                
                        } catch (PDOException $e) {
                            $this->loggerfile->debug('Egreso no anulado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el egreso'];
                        }
                    break;
                }
            }else{
                
                // Controlando error por no encontrar egreso a anular
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró el egreso solicitado'];
                $this->respuestaData['formAnularEgreso'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'egresoDetail');
    }
    
}
  