<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\ServicioAdicionalRcbValidation;
use App\Models\ServicioAdicionalRcb;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\Contrato;


class ServicioAdicionalRcbController extends BaseController{
    
    private $loggerfile;
    private $servicioViewsPath = '/administration/servicioadicionalrcb/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo servicio adicional recibo
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewServAdicionalRcb()
    {
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbNew');
    }
    

    /**
     * Método guarda nuevo servicio adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewServAdicionalRcb(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oServicioAdicionalRcbValidation = new ServicioAdicionalRcbValidation();
        $validation = $oServicioAdicionalRcbValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoServAdicionalRcb'] = $postData;
        } else {
            
            // Verificando existencia de contrato al que se asignará el servicio adicional recibo
            $contrato = Contrato::find($postData['contrato']);
            if (!is_null($contrato)) {
                
                // Un servicio adicional recibo sólo se podra crear si el contrato tiene estado activo
                if ($contrato->CTO_ESTADO == 1) {
                    
                    try{
                        $oServicioAdicionalRcb = new ServicioAdicionalRcb();
                        $oServicioAdicionalRcb->SAR_DESCRIPCION = $postData['descripcion'];
                        $oServicioAdicionalRcb->SAR_COSTO = $postData['costo'];
                        $oServicioAdicionalRcb->SAR_ESTADO = 1;
                        $oServicioAdicionalRcb->CTO_CODIGO = $contrato->CTO_CODIGO;
                        $oServicioAdicionalRcb->save();
                        
                        // Redireccionamos a la vista detalle de servicio adicional recibo
                        return new RedirectResponse('/montoadicionalrecibo/detalle/'.$oServicioAdicionalRcb->SAR_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Servicio adicional recibo no resgistrado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el monto adicional'];
                    }
                    
                }else{
                    // Controlando errores de validación porque el contrato no esta activo
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['El contrato no está activo'];
                    $this->respuestaData['formNuevoServAdicionalRcb'] = $postData;
                }
                
            }else{
                // Controlando errores de validación por no encontrar contrato
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['No se encontró el código de contrato solicitado'];
                $this->respuestaData['formNuevoServAdicionalRcb'] = $postData;
            }
        }

        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbNew');
    }
    
    /**
     * Método muestra lista de servicios adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListServAdicionalRcb(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) ServicioAdicionalRcb::where('SAR_ESTADO','=', 1)->count();
        $pagination = ServicioAdicionalRcb::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['serviciosAdicionalRcb'] = ServicioAdicionalRcb::where('SAR_ESTADO','=', 1)
                                                                                    ->offset($pagination['paginaOffset'])
                                                                                    ->limit($pagination['paginaLimit'])
                                                                                    ->orderBy('SAR_CODIGO', 'desc')
                                                                                    ->get()->toArray();
        }else{
            $this->respuestaData['serviciosAdicionalRcb'] = [];
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbList');
    }
    
    
    /**
     * Método muestra lista de servicios adicional recibo según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListServAdicionalRcb(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar servicios
        $oServicioAdicionalRcb = new ServicioAdicionalRcb();
        $oServicioAdicionalRcb->where('SAR_ESTADO', '=', 1);
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oServicioAdicionalRcb = $oServicioAdicionalRcb->where('SAR_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterFecha']) && $queryData['filterFecha'] != '' && validateDate($queryData['filterFecha'])) {
            $oServicioAdicionalRcb = $oServicioAdicionalRcb->whereDate('SAR_CREATED', '=', $queryData['filterFecha']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oServicioAdicionalRcb->count();
        $pagination = ServicioAdicionalRcb::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListServAdicionalRcb'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['serviciosAdicionalRcb'] = $oServicioAdicionalRcb->where('SAR_ESTADO', '=', 1)
                                                                                    ->offset($pagination['paginaOffset'])
                                                                                    ->limit($pagination['paginaLimit'])
                                                                                    ->orderBy('SAR_CODIGO', 'desc')
                                                                                    ->get()->toArray();
        }else{
            $this->respuestaData['serviciosAdicionalRcb'] = [];
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbList');
    }
    
    /**
     * Método muestra todos los datos de servicio adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailServAdicionalRcb(ServerRequest $request)
    {
        $codeServAdicionalRcb = (int) $request->getAttribute('servAdicionalRcbId');
        
        $servicioAdicionalRcb = ServicioAdicionalRcb::find($codeServAdicionalRcb);
        
        if($servicioAdicionalRcb != null){
            $this->respuestaData['servicioAdicionalRcb'] = $servicioAdicionalRcb->toArray();
            $contrato = $servicioAdicionalRcb->contrato;
            $this->respuestaData['contrato'] = $contrato->toArray();
            $predio = $contrato->predio;
            $this->respuestaData['predio'] = $predio->toArray();
            $this->respuestaData['cliente'] = $predio->cliente->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el monto adicional solicitado'];
            $this->respuestaData['servAdicionalRcbId'] = $codeServAdicionalRcb;
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de servicio adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditServAdicionalRcb(ServerRequest $request) {
        
        $codeServAdicionalRcb = (int) $request->getAttribute('servAdicionalRcbId');
        
        $servicioAdicionalRcb = ServicioAdicionalRcb::find($codeServAdicionalRcb);
        
        if($servicioAdicionalRcb != null){
            $this->respuestaData['servicioAdicionalRcb'] = $servicioAdicionalRcb->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el monto adicional solicitado'];
            $this->respuestaData['servAdicionalRcbId'] = $codeServAdicionalRcb;
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbEdit');
    }
    
    
    /**
     * Método para actualizar datos de servicio adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateServAdicionalRcbData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oServicioAdicionalRcbValidation = new ServicioAdicionalRcbValidation();
        $validation = $oServicioAdicionalRcbValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
        } else {
            
            // Servicio adicional recibo a editar
            $servicioAdicionalRcb = ServicioAdicionalRcb::find($postData['codigo']);
            
            if(!is_null($servicioAdicionalRcb)){
                
                // Los servicios adicionales solo se podrán modificar si aún no han sido agregados a algún recibo
                if ($servicioAdicionalRcb->SAR_ESTADO == 1) {
                    
                    // Verificando existencia de contrato al que se asignará el servicio adicional recibo
                    $contrato = Contrato::find($postData['contrato']);
                    if (!is_null($contrato)) {
                        
                        // Un servicio adicional recibo solo podra actualizar el codigo de contrato si este tiene un estado activo
                        if ($contrato->CTO_ESTADO == 1) {
                            
                            try{
                                
                                $servicioAdicionalRcb->SAR_DESCRIPCION = $postData['descripcion'];
                                $servicioAdicionalRcb->SAR_COSTO = $postData['costo'];
                                $servicioAdicionalRcb->CTO_CODIGO = $contrato->CTO_CODIGO;
                                $servicioAdicionalRcb->save();
                                
                                // Redireccionamos a la vista detalle de servicio adicional recibo
                                return new RedirectResponse('/montoadicionalrecibo/detalle/'.$servicioAdicionalRcb->SAR_CODIGO);
                                
                            }catch(PDOException $e){
                                $this->loggerfile->debug('Servicio adicional recibo no actualizado. Mensaje:'.$e->getMessage());
                                $this->respuestaCodigo = 500;
                                $this->respuestaEstado = 'error de servidor';
                                $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de monto adicional'];
                            }
                            
                        }else{
                            // Controlando errores de validación porque el contato no está activo
                            $this->respuestaCodigo = 400;
                            $this->respuestaEstado = 'error de validación';
                            $this->respuestaEstadoDetalle = ['El contrato no está activo'];
                            $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
                        }
                        
                    }else{
                        // Controlando errores de validación por no encontrar contrato
                        $this->respuestaCodigo = 400;
                        $this->respuestaEstado = 'error de validación';
                        $this->respuestaEstadoDetalle = ['No se encontró el código de contrato solicitado'];
                        $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
                    }
                    
                }else{
                    
                    // Controlando errores de validación porque el servicio adicional recibo ya fue agregado a algún recibo
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['No se puede modificar el monto adicional porque ya fue agregado al recibo'];
                    $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar servicio adicional recibo a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el monto adicional solicitado'];
                $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbEdit');
    }
    
    /**
     * Método elimina servicio adicional recibo
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deleteServAdicionalRcb(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oServicioAdicionalRcbValidation = new ServicioAdicionalRcbValidation();
        $validation = $oServicioAdicionalRcbValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarServAdicionalRcb'] = $postData;
        } else {
            
            // Servicio adicional recibo a eliminar
            $servicioAdicionalRcb = ServicioAdicionalRcb::find($postData['codigo']);
            
            if(!is_null($servicioAdicionalRcb)){
                
                // Los servicios adicionales solo se podrán eliminar si aún no han sido agregados a algún recibo
                if ($servicioAdicionalRcb->SAR_ESTADO == 1) {
                    
                    try{
                        
                        $servicioAdicionalRcb->forceDelete();
                        
                        // Redireccionamos a la vista detalle de servicio adicional recibo
                        return new RedirectResponse('/montoadicionalrecibo/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Servicio adicional recibo no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el monto adicional'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación porque el servicio adicional ya fue agregado a algún recibo
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['No se puede eliminar el monto adicional porque ya fue agregado al recibo'];
                    $this->respuestaData['formEditarServAdicionalRcb'] = $postData;
                }
                
            }else{
                
                // Controlando error por no encontrar servicio a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el monto adicional solicitado'];
                $this->respuestaData['formEliminarServAdicionalRcb'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioAdicionalRcbDetail');
    }
    
}
  