<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\SectorValidation;
use App\Models\Calle;
use App\Models\Sector;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;

class SectorController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/sector/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo sector
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewSector()
    {
        return $this->renderHTML($this->viewsPath.'sectorNew');
    }
    

    /**
     * Método guarda nuevo sector
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewSector(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oSectorValidation = new SectorValidation();
        $validation = $oSectorValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoSector'] = $postData;
        } else {
            
            // Los nombres de sector no deben repetirse (son llave unica)
            if (is_null(Sector::where('STR_NOMBRE', '=', $postData['nombre'])->first())) {
                
                try{
                    $oSector = new Sector();
                    $oSector->STR_NOMBRE = $postData['nombre'];
                    $oSector->save();
                    
                    // Redireccionamos a la vista detalle de sector
                    return new RedirectResponse('/sector/detalle/'.$oSector->STR_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Sector no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el sector'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de sector
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de sector no disponible'];
                $this->respuestaData['formNuevoSector'] = $postData;
            }

            
        }

        // Asignar las calles a la data a enviar
        $this->respuestaData['calles'] = Calle::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'sectorNew');
    }
    
    /**
     * Método muestra lista de sectores
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListSectores(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Sector::count();
        $pagination = Sector::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['sectores'] = Sector::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['sectores'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'sectorList');
    }
    
    
    /**
     * Método muestra lista de sectores según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListSectores(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar sectores
        $oSector = new Sector();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oSector = $oSector->where('STR_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterNombre']) && $queryData['filterNombre'] != '') {
            $oSector = $oSector->where('STR_NOMBRE', '=', $queryData['filterNombre']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oSector->count();
        $pagination = Sector::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListSectores'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['sectores'] = $oSector->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['sectores'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'sectorList');
    }
    
    /**
     * Método muestra todos los datos de sector
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailSector(ServerRequest $request)
    {
        $codeSector = (int) $request->getAttribute('sectorId');
        
        $sector = Sector::find($codeSector);
        
        if($sector != null){
            $this->respuestaData['sector'] = $sector->toArray();
            $this->respuestaData['calles'] = $sector->calles->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el sector solicitado'];
            $this->respuestaData['sectorId'] = $codeSector;
        }
        
        return $this->renderHTML($this->viewsPath.'sectorDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de sector
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditSector(ServerRequest $request) {
        
        $codeSector= (int) $request->getAttribute('sectorId');
        
        $sector = Sector::find($codeSector);
        
        if($sector != null){
            $this->respuestaData['sector'] = $sector->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el sector solicitado'];
            $this->respuestaData['sectorId'] = $codeSector;
        }
        
        return $this->renderHTML($this->viewsPath.'sectorEdit');
    }
    
    
    /**
     * Método para actualizar datos de sector
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateSectorData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oSectorValidation = new SectorValidation();
        $validation = $oSectorValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarSector'] = $postData;
        } else {
            
            // Sector a editar
            $oSector = Sector::find($postData['codigo']);
            
            if(!is_null($oSector)){
                
                // Los nombres de sector no deben repetirse (son llave unica)
                if (Sector::where('STR_NOMBRE', '=', $postData['nombre'])->where('STR_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        $oSector->STR_NOMBRE = $postData['nombre'];
                        $oSector->save();
                        
                        // Redireccionamos a la vista detalle de sector
                        return new RedirectResponse('/sector/detalle/'.$oSector->STR_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Sector no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de sector'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de sector
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de sector no disponible'];
                    $this->respuestaData['formEditarSector'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar sector a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el sector solicitado'];
                $this->respuestaData['formEditarSector'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'sectorEdit');
    }
    
    /**
     * Método elimina sector
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deleteSector(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oSectorValidation = new SectorValidation();
        $validation = $oSectorValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarSector'] = $postData;
        } else {
            
            // Sector a eliminar
            $oSector = Sector::find($postData['codigo']);
            
            if(!is_null($oSector)){
                
                // Se eliminará el sector si no tiene alguna calle asociada
                $calle = $oSector->calles->first();
                if(is_null($calle)){
                    
                    try{
                        $oSector->delete();
                        
                        // Redireccionamos a la vista lista de sectores
                        return new RedirectResponse('/sector/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Sector no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el sector'];
                    }
                    
                }else{
                    
                    // Enviando mensaje por no poder eliminar sector ya que tiene algún predio asociado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['No se puede eliminar el sector por tener predios asociados'];
                    $this->respuestaData['formEliminarSector'] = $postData;
                    
                    // Asignando datos  a la data a enviar
                    $this->respuestaData['sector'] = $oSector->toArray();
                    $this->respuestaData['calles'] = $oSector->calles->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar sector a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el sector solicitado'];
                $this->respuestaData['formEliminarSector'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'sectorDetail');
    }
    
}
  