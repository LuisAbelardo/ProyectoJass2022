<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Validation\ProyectoValidation;
use App\Models\Proyecto;
use App\Models\Contrato;
use Illuminate\Database\Capsule\Manager as Capsule;


class ProyectoController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/proyecto/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario nuevo proyecto
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewProyecto(ServerRequest $request)
    {
        return $this->renderHTML($this->viewsPath.'proyectoNew');
    }
    

    /**
     * Método guarda nuevo proyecto
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewProyecto(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oProyectoValidation = new ProyectoValidation();
        $validation = $oProyectoValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoProyecto'] = $postData;
        } else {
            
            try{
                // Cantidad de contratos activos
                $cantContratosActivos = (int) Contrato::where('CTO_ESTADO', '=', 1)->count();
                
                $oProyecto = new Proyecto();
                $oProyecto->PTO_NOMBRE = $postData['nombre'];
                $oProyecto->PTO_MNTO_CTO = $postData['montoPorContrato'];
                $oProyecto->PTO_NUM_CUOTAS = $postData['nroCuotasPorContrato'];
                $oProyecto->PTO_MNTO_TOTAL = $postData['montoPorContrato'] * $cantContratosActivos;
                $oProyecto->PTO_DESCRIPCION = $postData['descripcion'];
                $oProyecto->PTO_ESTADO = 1;
                $oProyecto->save();
                
                // Redireccionamos a la vista detalle de proyecto
                return new RedirectResponse('/proyecto/detalle/'.$oProyecto->PTO_CODIGO);
                    
            }catch(PDOException $e){
                $this->loggerfile->debug('Proyecto no resgistrado. Mensaje:'.$e->getMessage());
                $this->respuestaCodigo = 500;
                $this->respuestaEstado = 'error de servidor';
                $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el proyecto'];
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoNew');
    }
    
    
    
    /**
     * Método confirma proyecto
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function confirmProyecto(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oProyectoValidation = new ProyectoValidation();
        $validation = $oProyectoValidation->verifyRulesConfirm($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formConfirmarProyecto'] = $postData;
        } else {
            
            // Proyecto a confirmar
            $proyecto = Proyecto::find($postData['codigo']);
            if(!is_null($proyecto)){
                
                if($proyecto->PTO_ESTADO == 1){
                    
                    try {
                        
                        Capsule::transaction(function() use($proyecto, $postData){
                            
                            // Actualizar estado de proyecto a confirmado
                            $proyecto->PTO_ESTADO = 2;
                            $proyecto->save();
                            
                            // Creamos cuotas extraordinarias
                            $pdo = Capsule::connection()->getPdo();
                            $stmt=$pdo->prepare('CALL sp_set_proyecto_cuotas(?, @msj)');
                            $stmt->execute([$proyecto->PTO_CODIGO]);
                            $codeRptaBD = Capsule::select("SELECT @msj AS RESPUESTA");
                            
                            if($codeRptaBD[0]->RESPUESTA != 200){
                                throw new \Exception('Error inesperado al crear cuotas extraordinarias');
                            }
                        });
                        
                        // Redireccionamos a la vista detalle de proyecto
                        return new RedirectResponse('/proyecto/detalle/'.$proyecto->PTO_CODIGO);
                        
                    } catch (PDOException $e) {
                        $this->loggerfile->debug('Proyecto no confirmado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se confirmó el proyecto'];
                    }
                    
                }else{
                    // Controlando error por que el proyecto no puede ser confirmado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $msj = "El proyecto no puede ser confirmado";
                    if ($proyecto->PTO_ESTADO == 2) {
                        $msj = 'El proyecto ya fue confirmado';
                    }elseif ($proyecto->PTO_ESTADO == 3){
                        $msj = 'El proyecto no se puede confirmar ya que fue anulado';
                    }
                    $this->respuestaEstadoDetalle = [$msj];
                    $this->respuestaData['formConfirmarProyecto'] = $postData;
                    
                    // Asignar datos a enviar
                    $this->respuestaData['proyecto'] = $proyecto->toArray();
                    
                }
                
            }else{
                
                // Controlando error por no encontrar proyecto a confirmar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el proyecto solicitado'];
                $this->respuestaData['formConfirmarProyecto'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoDetail');
    }
    
    
    
    /**
     * Método muestra lista de proyectos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListProyectos(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Proyecto::count();
        $pagination = Proyecto::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['proyectos'] = Proyecto::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('PTO_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['proyectos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoList');
    }
    
    
    /**
     * Método muestra lista de proyectos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListProyectos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar proyectos
        $oProyecto = new Proyecto();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oProyecto = $oProyecto->where('PTO_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterFecha']) && $queryData['filterFecha'] != '' && validateDate($queryData['filterFecha'])){
            $oProyecto = $oProyecto->whereDate('PTO_CREATED', '=', $queryData['filterFecha']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $oProyecto = $oProyecto->where('PTO_ESTADO', '=', $queryData['filterEstado']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oProyecto->count();
        $pagination = Proyecto::paginate($cantidadRegistros, $paginaActual);
            
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListProyectos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['proyectos'] = $oProyecto->offset($pagination['paginaOffset'])
                                                                ->limit($pagination['paginaLimit'])
                                                                ->orderBy('PTO_CODIGO', 'desc')
                                                                ->get()->toArray();
        }else{
            $this->respuestaData['proyectos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoList');
    }
    
    
    /**
     * Método muestra todos los datos de proyecto
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailProyecto(ServerRequest $request)
    {
        $codeProyecto = (int) $request->getAttribute('proyectoId');
        
        $proyecto = Proyecto::find($codeProyecto);
        
        if($proyecto != null){
            $this->respuestaData['proyecto'] = $proyecto->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el proyecto solicitado'];
            $this->respuestaData['proyectoId'] = $codeProyecto;
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoDetail');
    }
    
    
    /**
     * Método anula proyecto
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function AnnularProyecto(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oProyectoValidation = new ProyectoValidation();
        $validation = $oProyectoValidation->veryRulesAnnular($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formAnularProyecto'] = $postData;
        } else {
            
            // Proyecto a anular
            $proyecto = Proyecto::find($postData['codigo']);
            if(!is_null($proyecto)){
                
                if($proyecto->PTO_ESTADO == 1){
                    
                    try {
                        
                        // Actualizar estado de proyecto a anulado
                        $proyecto->PTO_ESTADO = 3;
                        $proyecto->save();
                            
                        // Redireccionamos a la vista detalle de proyecto
                        return new RedirectResponse('/proyecto/detalle/'.$proyecto->PTO_CODIGO);
                            
                    } catch (PDOException $e) {
                        $this->loggerfile->debug('Proyecto no anulado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el proyecto'];
                    }
                    
                }else{
                    // Controlando error por que el proyecto no puede ser anulado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $msj = "El proyecto no puede ser eliminado";
                    if ($proyecto->PTO_ESTADO == 2) {
                        $msj = 'El proyecto no puede ser anulado porque este ya fue confirmado';
                    }elseif ($proyecto->PTO_ESTADO == 3){
                        $msj = 'El proyecto ya fue anulado';
                    }
                    $this->respuestaEstadoDetalle = [$msj];
                    $this->respuestaData['formAnularProyecto'] = $postData;
                    
                    // Asignar datos a enviar
                    $this->respuestaData['proyecto'] = $proyecto->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar proyecto a anular
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el proyecto solicitado'];
                $this->respuestaData['formAnularProyecto'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'proyectoDetail');
    }
    
}
  