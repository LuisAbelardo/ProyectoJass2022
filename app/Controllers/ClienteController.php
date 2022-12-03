<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\ClienteValidation;
use App\Models\Cliente;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;


class ClienteController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/cliente/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo cliente natural
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewClienteNatural()
    {
        return $this->renderHTML($this->viewsPath.'clienteNewNatural');
    }
    
    /**
     * Método muestra formulario para registrar nuevo cliente juridico
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewClienteJuridico()
    {
        return $this->renderHTML($this->viewsPath.'clienteNewJuridico');
    }
    

    /**
     * Método guarda nuevo cliente
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewCliente(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oClienteValidation = new ClienteValidation();
        $validation = $oClienteValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoCliente'] = $postData;//d($this->respuestaEstadoDetalle, $this->respuestaData);exit();
        } else {
            
            // Buscando existencia de cliente
            $uniqueAttribute = ($postData['tipo'] == 1) ? 'DNI' : 'RUC';
            $cliente = Cliente::where('CLI_DOCUMENTO', '=', $postData['documento'])->first();
            
            // El doucmento (DNI o RUC) es un atributo unico, y no debe repetirse
            if (is_null($cliente)) {
                
                try{
                    
                    $oCliente = new Cliente();
                    $oCliente->CLI_DOCUMENTO = $postData['documento'];
                    $oCliente->CLI_NOMBRES = $postData['nombres'];
                    $oCliente->CLI_DEPARTAMENTO = $postData['departamento'];
                    $oCliente->CLI_PROVINCIA = $postData['provincia'];
                    $oCliente->CLI_DISTRITO = $postData['distrito'];
                    $oCliente->CLI_DIRECCION = $postData['direccion'];
                    $oCliente->CLI_TELEFONO = $postData['telefono'];
                    $oCliente->CLI_EMAIL = $postData['email'];
                    
                    if ($postData['tipo'] == 1) {
                        $oCliente->CLI_TIPO = 1;
                        $oCliente->CLI_FECHA_NAC = $postData['fechanacimiento'];
                        
                    }else{
                        $oCliente->CLI_TIPO = 2;
                        $oCliente->CLI_REPRES_LEGAL = $postData['representantelegal'];
                    }
                    
                    $oCliente->save();
                    
                    // Redireccionamos a la vista detalle de cliente
                    return new RedirectResponse('/cliente/detalle/'.$oCliente->CLI_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Cliente no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el cliente'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de documento (DNI o RUC)
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ["{$uniqueAttribute} no disponible"];
                $this->respuestaData['formNuevoCliente'] = $postData;
            }
        }
        
        // Pagina de retorno
        $pageReturn = ($postData['tipo'] == 1) ? 'clienteNewNatural' : 'clienteNewJuridico';

        return $this->renderHTML($this->viewsPath.$pageReturn);
    }
    
    /**
     * Método muestra lista de clientes
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListClientes(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Cliente::count();
        $pagination = Cliente::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['clientes'] = Cliente::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('CLI_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['clientes'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'clienteList');
    }
    
    
    /**
     * Método muestra lista de clientes según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListClientes(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar clientes
        $oCliente = new Cliente();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oCliente = $oCliente->where('CLI_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterDocumento']) && $queryData['filterDocumento'] != ''){
            $oCliente = $oCliente->where('CLI_DOCUMENTO', '=', $queryData['filterDocumento']);
        }
        if(isset($queryData['filterNombres']) && $queryData['filterNombres'] != '') {
            $oCliente = $oCliente->where('CLI_NOMBRES', 'LIKE', '%'. $queryData['filterNombres'].'%');
        }
        if(isset($queryData['filterTipo']) && $queryData['filterTipo'] != ''
                && is_numeric($queryData['filterTipo']) && $queryData['filterTipo'] != -1) {
            $oCliente = $oCliente->where('CLI_TIPO', '=', $queryData['filterTipo']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $oCliente->count();
        $pagination = Cliente::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListClientes'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['clientes'] = $oCliente->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('CLI_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['clientes'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'clienteList');
    }
    
    /**
     * Método muestra todos los datos de cliente
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailCliente(ServerRequest $request)
    {
        $codeCliente = $request->getAttribute('clienteId');
        
        $cliente = Cliente::find($codeCliente);
        
        if($cliente != null){
            $this->respuestaData['cliente'] = $cliente->toArray();
            $predios = $cliente->predios;
            $this->respuestaData['predios'] = $predios->toArray();
            $this->respuestaData['contratos'] = [];
            foreach ($predios as $key => $value) {
                $this->respuestaData['contratos'] = array_merge($this->respuestaData['contratos'], $value->contratos->toArray());  
            } 
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el cliente solicitado'];
            $this->respuestaData['clienteId'] = $codeCliente;
        }
        
        return $this->renderHTML($this->viewsPath.'clienteDetail');
    }
    
    /**
     * Método muestra todos los datos de cliente apartir del documento de identidad
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailClienteFromDocJson(ServerRequest $request)
    {
        $docCliente = (int) $request->getAttribute('clienteDoc');
        
        $cliente = Cliente::where('CLI_DOCUMENTO', '=', $docCliente)->first();
        
        if($cliente != null){
            $this->respuestaData['cliente']['documento'] = $cliente->CLI_DOCUMENTO;
            $this->respuestaData['cliente']['nombre'] = $cliente->CLI_NOMBRES;
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el cliente solicitado'];
            $this->respuestaData['clienteId'] = $docCliente;
        }
        
        return $this->responseJSON();
    }
    
    /**
     * Método muestra la vista para editar datos de cliente
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditCliente(ServerRequest $request) {
        
        $pageReturn = 'clienteEditNatural';
        $codeCliente = (int) $request->getAttribute('clienteId');
        
        $cliente = Cliente::find($codeCliente);
        
        if($cliente != null){
            $this->respuestaData['cliente'] = $cliente->toArray();
            $pageReturn = ($cliente->CLI_TIPO == 1) ? 'clienteEditNatural' : 'clienteEditJuridico';
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el cliente solicitado'];
            $this->respuestaData['clienteId'] = $codeCliente;
        }
        
        return $this->renderHTML($this->viewsPath.$pageReturn);
    }
    
    
    /**
     * Método para actualizar datos de cliente
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateClienteData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oClienteValidation = new ClienteValidation();
        $validation = $oClienteValidation->veryRulesUpdate($postData);
        
        // Pagina de retorno por defecto segun el parametro (tipo) recibido
        $pageReturn = ($postData['tipo'] == 1) ? 'clienteEditNatural' : 'clienteEditJuridico';
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarCliente'] = $postData;
        } else {
            
            // Cliente a editar
            $oCliente = Cliente::find($postData['codigo']);
            
            if(!is_null($oCliente)){
                
                // Pagina de retorno asignado según el tipo de cliente
                $pageReturn = ($postData['tipo'] == 1) ? 'clienteEditNatural' : 'clienteEditJuridico';
                
                // Buscando existencia de cliente con mismo documento (DNI o RUC) que se intenta guardar
                $uniqueAttribute = ($postData['tipo'] == 1) ? 'DNI' : 'RUC';
                $cliente = Cliente::where('CLI_DOCUMENTO', '=', $postData['documento'])
                                    ->where('CLI_CODIGO', '<>', $postData['codigo'])->first();
                
                // El doucmento (DNI o RUC) es un atributo unico, y no debe repetirse
                if (is_null($cliente)) {
                    
                    try{
                        
                        $oCliente->CLI_DOCUMENTO = $postData['documento'];
                        $oCliente->CLI_NOMBRES = $postData['nombres'];
                        $oCliente->CLI_DEPARTAMENTO = $postData['departamento'];
                        $oCliente->CLI_PROVINCIA = $postData['provincia'];
                        $oCliente->CLI_DISTRITO = $postData['distrito'];
                        $oCliente->CLI_DIRECCION = $postData['direccion'];
                        $oCliente->CLI_TELEFONO = $postData['telefono'];
                        $oCliente->CLI_EMAIL = $postData['email'];
                        
                        if ($oCliente->CLI_TIPO == 1) {
                            $oCliente->CLI_FECHA_NAC = $postData['fechanacimiento'];
                            
                        }else{
                            $oCliente->CLI_REPRES_LEGAL = $postData['representantelegal'];
                        }
                        
                        $oCliente->save();
                        
                        // Redireccionamos a la vista detalle de cliente
                        return new RedirectResponse('/cliente/detalle/'.$oCliente->CLI_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Cliente no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de cliente'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de DNI o RUC
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ["{$uniqueAttribute} no disponible"];
                    $this->respuestaData['formEditarCliente'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar cliente a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el cliente solicitado'];
                $this->respuestaData['formEditarCliente'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.$pageReturn);
    }
    
    /**
     * Método elimina cliente
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function deleteCliente(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oClienteValidation = new ClienteValidation();
        $validation = $oClienteValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarCliente'] = $postData;
        } else {
            
            // Cliente a eliminar
            $oCliente = Cliente::find($postData['codigo']);
            
            if(!is_null($oCliente)){
                
                // Se eliminará el sector si no tiene algún predio asociado
                $predio = $oCliente->predios->first();
                if(is_null($predio)){
                    
                    try{
                        $oCliente->delete();
                        
                        // Rediccionamos a la vista lista de clientes
                        return new RedirectResponse('/cliente/lista');
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Cliente no eliminado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el cliente'];
                    }
                    
                }else{
                    
                    // Enviando mensaje por no poder eliminar cliente ya que tiene algún predio asociado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['No se puede eliminar el cliente por tener predios asociados'];
                    $this->respuestaData['formEliminarCliente'] = $postData;
                    
                    // Asignando datos  a la data a enviar
                    $this->respuestaData['cliente'] = $oCliente->toArray();
                    $this->respuestaData['predios'] = $oCliente->predios->toArray();
                }
                
            }else{
                
                // Controlando error por no encontrar cliente a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el cliente solicitado'];
                $this->respuestaData['formEliminarCliente'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'clienteDetail');
    }
    
}
  