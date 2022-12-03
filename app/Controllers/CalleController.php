<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\CalleValidation;
use App\Models\Calle;
use App\Models\Sector;
use PDOException;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\SectorCalle;


class CalleController extends BaseController{
    
    private $loggerfile;
    private $calleViewsPath = '/administration/calle/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nueva calle
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewCalle()
    {
        // Asignar sectores a la data a enviar
        $this->respuestaData['sectores'] = Sector::all()->toArray();
        
        return $this->renderHTML($this->calleViewsPath.'calleNew');
    }
    

    /**
     * Método guarda nueva calle
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewCalle(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oCalleValidation = new CalleValidation();
        $validation = $oCalleValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevaCalle'] = $postData;
        } else {
            
            // Los nombres de calle no deben repetirse (son llave unica)
            if (is_null(Calle::where('CAL_NOMBRE', '=', $postData['nombre'])->first())) {
                
                try{
                    $oCalle = new Calle();
                    
                    Capsule::transaction(function() use($oCalle, $postData){
                        $oCalle->CAL_NOMBRE = $postData['nombre'];
                        $oCalle->save();
                        
                        foreach ($postData['sectores'] as $sector) {
                            $oSectorCalle = new SectorCalle();
                            $oSectorCalle->STR_CODIGO = $sector;
                            $oSectorCalle->CAL_CODIGO = $oCalle->CAL_CODIGO;
                            $oSectorCalle->save();
                        }
                    });
                    
                    
                    // Redireccionamos a la vista detalle de calle
                    return new RedirectResponse('/calle/detalle/'.$oCalle->CAL_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Calle no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó la calle'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de calle
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de calle no disponible'];
                $this->respuestaData['formNuevaCalle'] = $postData;
            }

            
        }
        
        // Asignar sectores a la data a enviar
        $this->respuestaData['sectores'] = Sector::all()->toArray();

        return $this->renderHTML($this->calleViewsPath.'calleNew');
    }
    
    /**
     * Método muestra lista de calles
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListCalles(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Calle::count();
        $pagination = Calle::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['calles'] = Calle::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('CAL_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['calles'] = [];
        }
        
        return $this->renderHTML($this->calleViewsPath.'calleList');
    }
    
    
    /**
     * Método muestra lista de calles según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListCalles(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar calles
        $oCalle = new Calle();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oCalle = $oCalle->where('CAL_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterNombre']) && $queryData['filterNombre'] != '') {
            $oCalle = $oCalle->where('CAL_NOMBRE', 'LIKE', $queryData['filterNombre'].'%');
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oCalle->count();
        $pagination = Calle::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListCalles'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['calles'] = $oCalle->offset($pagination['paginaOffset'])
                                                        ->limit($pagination['paginaLimit'])
                                                        ->orderBy('CAL_CODIGO', 'desc')
                                                        ->get()->toArray();
        }else{
            $this->respuestaData['calles'] = [];
        }
        
        return $this->renderHTML($this->calleViewsPath.'calleList');
    }
    
    /**
     * Método muestra todos los datos de calle
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailCalle(ServerRequest $request)
    {
        $codeCalle = (int) $request->getAttribute('calleId');
        
        $calle = Calle::find($codeCalle);
        
        if($calle != null){
            $this->respuestaData['calle'] = $calle->toArray();
            $this->respuestaData['sectores'] = $calle->sectores->toArray();
            $this->respuestaData['predios'] = $calle->predios->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro la calle solicitada'];
            $this->respuestaData['calleId'] = $codeCalle;
        }
        
        return $this->renderHTML($this->calleViewsPath.'calleDetail');
    }
    
    /**
     * Método muestra la vista para editar datos de calle
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditCalle(ServerRequest $request) {
        
        $codeCalle = (int) $request->getAttribute('calleId');
        
        $calle = Calle::find($codeCalle);
        
        if($calle != null){
            $this->respuestaData['calle'] = $calle->toArray();
            $this->respuestaData['sectoresCalle'] = $calle->sectores->toArray();
            $this->respuestaData['sectores'] = Sector::all()->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro la calle solicitada'];
            $this->respuestaData['calleId'] = $codeCalle;
        }
        
        return $this->renderHTML($this->calleViewsPath.'calleEdit');
    }
    
    
    /**
     * Método para actualizar datos de calle
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateCalleData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oCalleValidation = new CalleValidation();
        $validation = $oCalleValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarCalle'] = $postData;
        } else {
            
            // Calle a editar
            $oCalle = Calle::find($postData['codigo']);
            
            if(!is_null($oCalle)){
                
                // Los nombres de calle no deben repetirse (son llave unica)
                if (Calle::where('CAL_NOMBRE', '=', $postData['nombre'])->where('CAL_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        
                        Capsule::transaction(function() use($oCalle, $postData){
                            $oCalle->CAL_NOMBRE = $postData['nombre'];
                            $oCalle->save();
                            
                            $sectoresBD = $oCalle->sectores->toArray();
                            
                            // Eliminando sectores al que la calle ya no pertenece
                            foreach ($sectoresBD as $sectorBD) {
                                $borrarSectorBD = true;
                                foreach ($postData['sectores'] as $sector) {
                                    if ($sector == $sectorBD['STR_CODIGO']) {
                                        $borrarSectorBD = false;
                                    }
                                }
                                if($borrarSectorBD){
                                    SectorCalle::where('CAL_CODIGO', '=', $oCalle->CAL_CODIGO)
                                                    ->where('STR_CODIGO', '=', $sectorBD['STR_CODIGO'])->forceDelete();
                                }
                            }
                            
                            // Registrando sectores al que la calle pertenece
                            foreach ($postData['sectores'] as $sector) {
                                $registrarSector = true;
                                foreach ($sectoresBD as $sectorBD) {
                                    if ($sectorBD['STR_CODIGO'] == $sector) {
                                        $registrarSector = false;
                                    }
                                }
                                if($registrarSector){
                                    $oSectorCalle = new SectorCalle();
                                    $oSectorCalle->STR_CODIGO = $sector;
                                    $oSectorCalle->CAL_CODIGO = $oCalle->CAL_CODIGO;
                                    $oSectorCalle->save();
                                }
                            }
                        });
                            
                            
                        // Redireccionamos a la vista detalle de calle
                        return new RedirectResponse('/calle/detalle/'.$oCalle->CAL_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Calle no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de calle'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de calle
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de calle no disponible'];
                    $this->respuestaData['formEditarCalle'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar calle a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro la calle solicitada'];
                $this->respuestaData['formEditarCalle'] = $postData;
            }
            
        }
        
        // Asignar sectores a la data a enviar
        $this->respuestaData['sectores'] = Sector::all()->toArray();
        
        return $this->renderHTML($this->calleViewsPath.'calleEdit');
    }
    
    /**
     * Método elimina calle
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\RedirectResponse
     */
    public function deleteCalle(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oCalleValidation = new CalleValidation();
        $validation = $oCalleValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarCalle'] = $postData;
        } else {
            
            // Calle a eliminar
            $oCalle = Calle::find($postData['codigo']);
            
            if(!is_null($oCalle)){
                
                // Se eliminará la calle si no tiene algún predio asociado
                $sector = $oCalle->predios->first();
                if(is_null($sector)){
                    
                    try{
                        
                        Capsule::transaction(function() use($oCalle, $postData){
                            $oCalle->delete();
                            SectorCalle::where('CAL_CODIGO', '=', $oCalle->CAL_CODIGO)->delete();
                        });
                        
                        
                        // Retornamos la vista lista de calles
                        return new RedirectResponse('/calle/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Calle no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó la calle'];
                    }
                    
                }else{
                    
                    // Enviando mensaje por no poder eliminar calle ya que tiene algún sector asociado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['No se puede eliminar la calle por tener sectores asociados'];
                    $this->respuestaData['formEliminarCalle'] = $postData;
                    
                    // Asignando datos  a la data a enviar
                    $this->respuestaData['calle'] = $oCalle->toArray();
                    $this->respuestaData['sectores'] = $oCalle->sectores->toArray();
                }
                
                
                
            }else{
                
                // Controlando error por no encontrar calle a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro la calle solicitada'];
                $this->respuestaData['formEliminarCalle'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->calleViewsPath.'calleDetail');
    }
    
}
  