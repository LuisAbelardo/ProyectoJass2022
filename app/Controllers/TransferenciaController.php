<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laminas\Diactoros\ServerRequest;
use App\Libraries\LogMonolog;
use App\Models\Caja;
use App\Libraries\Session;
use App\Validation\TransferenciaValidation;
use App\Models\Egreso;
use App\Models\Ingreso;
use Laminas\Diactoros\Response\RedirectResponse;


class TransferenciaController extends BaseController{
    
    private $loggerfile;
    private $calleViewsPath = '/administration/transferencia/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nueva transferencia
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewTransferencia()
    {
        $cajas = Caja::all()->toArray();
        
        // Asignar cajas emisor a la data a enviar
        $this->respuestaData['cajasEmisor'] = $cajas;
        
        // Asignar cajas receptor a la data a enviar
        $this->respuestaData['cajasReceptor'] = $cajas;
        
        return $this->renderHTML($this->calleViewsPath.'transferenciaNew');
    }
    
    
    /**
     * Método guarda nueva transferencia
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewTransferencia(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oTransferenciaValidation = new TransferenciaValidation();
        $validation = $oTransferenciaValidation->verifyRulesNew($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevaTransferencia'] = $postData;
        } else {
            
            $cajaEmisor = Caja::find($postData['cajaEmisor']);
            $cajaReceptor = Caja::find($postData['cajaReceptor']);
            
            // Verificar caja Emisor
            if (!is_null($cajaEmisor)) {
                
                // Verificar caja Receptor
                if (!is_null($cajaReceptor)) {
                    
                    if ($cajaEmisor->CAJ_CODIGO != $cajaReceptor->CAJ_CODIGO) {
                        
                        try{
                        
                            $oEgreso = new Egreso();
                            $oIngreso = new Ingreso();
                            $operacionRealizada = false;
                            
                            Capsule::transaction(function() use($cajaEmisor, $cajaReceptor, $oEgreso, $oIngreso,$postData, &$operacionRealizada){
                                
                                if ($cajaEmisor->CAJ_SALDO >= $postData['monto']) {
                                    
                                    // REGRISTRAMOS EL EGRESO
                                    $oEgreso->EGR_TIPO = "TRANSF";
                                    $oEgreso->EGR_CANTIDAD = $postData['monto'];
                                    $oEgreso->EGR_TIPO_COMPROBANTE = 4;
                                    $oEgreso->EGR_DESCRIPCION = "{$postData['descripcion']}";
                                    $oEgreso->EGR_ESTADO = 1;
                                    $oEgreso->CAJ_CODIGO = $cajaEmisor->CAJ_CODIGO;
                                    $oEgreso->USU_CODIGO = Session::getUserValue('id');
                                    $oEgreso->save();
                                    
                                    // Actualizar saldo de caja emisor
                                    $cajaEmisor->CAJ_SALDO = $cajaEmisor->CAJ_SALDO - $oEgreso->EGR_CANTIDAD;
                                    $cajaEmisor->save();
                                    
                                    // REGISTRAMOS EL INGRESO
                                    $oIngreso->IGR_TIPO = "TRANSF";
                                    $oIngreso->IGR_CANTIDAD = $postData['monto'];
                                    $oIngreso->IGR_IGV = 0;
                                    $oIngreso->IGR_TIPO_COMPROBANTE = 4;
                                    $oIngreso->IGR_DESCRIPCION = "{$postData['descripcion']} (Transferencia Ref. Egreso: {$oEgreso->EGR_CODIGO})";
                                    $oIngreso->IGR_ESTADO = 1;
                                    $oIngreso->CAJ_CODIGO = $cajaReceptor->CAJ_CODIGO;
                                    $oIngreso->USU_CODIGO = Session::getUserValue('id');
                                    $oIngreso->save();
                                    
                                    // Actualizar saldo de caja receptor
                                    $cajaReceptor->CAJ_SALDO = $cajaReceptor->CAJ_SALDO + $oIngreso->IGR_CANTIDAD;
                                    $cajaReceptor->save();
                                    
                                    // Actualizar descripcion de egreso
                                    $oEgreso->EGR_DESCRIPCION = "{$postData['descripcion']} (Transferencia Ref. Ingreso: {$oIngreso->IGR_CODIGO})";
                                    $oEgreso->save();
                                    
                                    // CONFIRMAMOS EL EXITO DE LA OPERACION
                                    $operacionRealizada = true;
                                    
                                }else{
                                    // Controlando errores de validación por saldo insuficiente
                                    $this->respuestaCodigo = 400;
                                    $this->respuestaEstado = 'error de validación';
                                    $this->respuestaEstadoDetalle = ['Saldo insuficiente'];
                                    $this->respuestaData['formNuevaTransferencia'] = $postData;
                                }
                            });
                        
                            // Si efectuamos la operación redireccionamos al detalle de ingreso
                            if ($operacionRealizada) {
                                // Redireccionamos a la vista detalle de ingreso
                                return new RedirectResponse('/ingreso/detalle/'.$oIngreso->IGR_CODIGO);
                            }
                        
                        }catch(\PDOException $e){
                            $this->loggerfile->debug('Transferencia no realizada. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['No se guardó la transferencia. Ocurrio un error inesperado'];
                        }
                        
                    }else{
                        // Controlando errores de validación porque la caja origen es igual a la caja destino
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validación';
                        $this->respuestaEstadoDetalle = ['Caja destino debe ser diferente a la caja origen'];
                        $this->respuestaData['formNuevaTransferencia'] = $postData;
                    }
                    
                }else{
                    // Controlando errores de validación por no encontrar caja de destino
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['No se encontró la caja de destino'];
                    $this->respuestaData['formNuevaTransferencia'] = $postData;
                }
                
            }else{
                // Controlando errores de validación por no encontrar caja de origen
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['No se encontró la caja de origen'];
                $this->respuestaData['formNuevaTransferencia'] = $postData;
            }
            
        }
        
        $cajas = Caja::all()->toArray();
        // Asignar cajas emisor a la data a enviar
        $this->respuestaData['cajasEmisor'] = $cajas;
        // Asignar cajas receptor a la data a enviar
        $this->respuestaData['cajasReceptor'] = $cajas;
        
        return $this->renderHTML($this->calleViewsPath.'transferenciaNew');
    }
    
}
  