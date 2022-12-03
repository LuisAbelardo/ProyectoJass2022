<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\ServicioValidation;
use App\Models\Servicio;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;


class ServicioController extends BaseController{
    
    private $loggerfile;
    private $servicioViewsPath = '/administration/servicio/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo servicio
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewServicio()
    {
        return $this->renderHTML($this->servicioViewsPath.'servicioNew');
    }
    

    /**
     * Método guarda nuevo servicio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewServicio(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oServicioValidation = new ServicioValidation();
        $validation = $oServicioValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoServicio'] = $postData;
        } else {
            
            // Los nombres de servicio no deben repetirse (son llave unica)
            if (is_null(Servicio::where('SRV_NOMBRE', '=', $postData['nombre'])->first())) {
                
                try{
                    $oServicio = new Servicio();
                    $oServicio->SRV_NOMBRE = $postData['nombre'];
                    $oServicio->save();
                    
                    // Redireccionamos a la vista detalle de servicio
                    return new RedirectResponse('/servicio/detalle/'.$oServicio->SRV_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Servicio no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el servicio'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de servicio
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de servicio no disponible'];
                $this->respuestaData['formNuevoServicio'] = $postData;
            }

            
        }

        return $this->renderHTML($this->servicioViewsPath.'servicioNew');
    }
    
    /**
     * Método muestra lista de servicios
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListServicios(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Servicio::count();
        $pagination = Servicio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['servicios'] = Servicio::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['servicios'] = [];
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioList');
    }
    
    
    /**
     * Método muestra lista de servicios según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListServicios(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar servicios
        $oServicio = new Servicio();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oServicio = $oServicio->where('SRV_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterNombre']) && $queryData['filterNombre'] != '') {
            $oServicio = $oServicio->where('SRV_NOMBRE', '=', $queryData['filterNombre']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oServicio->count();
        $pagination = Servicio::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListServicios'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['servicios'] = $oServicio->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['servicios'] = [];
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioList');
    }
    
    /**
     * Método muestra todos los datos de servicio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailServicio(ServerRequest $request)
    {
        $codeServicio = (int) $request->getAttribute('servicioId');
        
        $servicio = Servicio::find($codeServicio);
        
        if($servicio != null){
            $this->respuestaData['servicio'] = $servicio->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el servicio solicitado'];
            $this->respuestaData['servicioId'] = $codeServicio;
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de servicio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditServicio(ServerRequest $request) {
        
        $codeServicio = (int) $request->getAttribute('servicioId');
        
        $servicio = Servicio::find($codeServicio);
        
        if($servicio != null){
            $this->respuestaData['servicio'] = $servicio->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el servicio solicitado'];
            $this->respuestaData['servicioId'] = $codeServicio;
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioEdit');
    }
    
    
    /**
     * Método para actualizar datos de servicio
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateServicioData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oServicioValidation = new ServicioValidation();
        $validation = $oServicioValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarServicio'] = $postData;
        } else {
            
            // Servicio a editar
            $oServicio = Servicio::find($postData['codigo']);
            
            if(!is_null($oServicio)){
                
                // Los nombres de servicio no deben repetirse (son llave unica)
                if (Servicio::where('SRV_NOMBRE', '=', $postData['nombre'])->where('SRV_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        $oServicio->SRV_NOMBRE = $postData['nombre'];
                        $oServicio->save();
                        
                        // Redireccionamos a la vista detalle de servicio
                        return new RedirectResponse('/servicio/detalle/'.$oServicio->SRV_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Servicio no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de servicio'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de servicio
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de servicio no disponible'];
                    $this->respuestaData['formEditarServicio'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar servicio a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el servicio solicitado'];
                $this->respuestaData['formEditarServicio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioEdit');
    }
    
    /**
     * Método elimina servicio (NO USADO)
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     *
    public function deleteServicio(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oServicioValidation = new ServicioValidation();
        $validation = $oServicioValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarServicio'] = $postData;
        } else {
            
            // Servicio a eliminar
            $oServicio = Servicio::find($postData['codigo']);
            
            if(!is_null($oServicio)){
                
                try{
                    $oServicio->delete();
                    
                    // Rediccionamos a la vista lista de servicios
                    return new RedirectResponse('/servicio/lista');
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Servicio no eliminado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el servicio'];
                }
                
            }else{
                
                // Controlando error por no encontrar servicio a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el servicio solicitado'];
                $this->respuestaData['formEliminarServicio'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->servicioViewsPath.'servicioDetail');
    }}*/
    
}
  