<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Models\Predio;
use App\Models\Servicio;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\LogMonolog;
use App\Models\TipoUsoPredio;
use App\Models\Contrato;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\ServicioContrato;
use App\Validation\ContratoValidation;
use App\Models\Financiamiento;
use App\Models\Proyecto;


class ContratoController extends BaseController{
    
    private $loggerfile;
    private $contratoViewsPath = '/administration/contrato/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo contrato
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewContrato()
    {
        // Asignar servicios a la data a enviar
        $this->respuestaData['servicios'] = Servicio::all()->toArray();
        // Asignar tipos de uso de predio a la data a enviar
        $this->respuestaData['tiposUsoPredio'] = TipoUsoPredio::all()->toArray();
        
        return $this->renderHTML($this->contratoViewsPath.'contratoNew');
    }
    

    /**
     * Método guarda nuevo contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewContrato(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoContrato'] = $postData;
        } else {
            
            // Verificando existencia de predio al que se asignará el contrato
            $predio = Predio::find($postData['predio']);
            if (!is_null($predio)) {
                
                $predioTramiteActivo = false;
                foreach ($predio->contratos as $contrato) {
                    if ($contrato->CTO_ESTADO == 0 || $contrato->CTO_ESTADO == 1) {
                        $predioTramiteActivo = true;
                        break;
                    }
                }
                
                // Se creará el contrato únicamente sí no hay algún contrato asociado al predio con estado en trámite o activo
                if (!$predioTramiteActivo) {
                    
                    try{
                        
                        $oContrato = new Contrato();
                        
                        Capsule::transaction(function() use($oContrato, $postData, $validation){
                            
                            $oContrato->PRE_CODIGO = $postData['predio'];
                            $oContrato->TUP_CODIGO = $postData['tipoUsoPredio'];
                            $oContrato->CTO_OBSERVACION = $postData['observacion'];
                            $oContrato->CTO_ESTADO = $postData['estado'];
                            $oContrato->CTO_FECHA_TRAMITE = date("Y-m-d");
                            
                            if ($postData['estado'] == 1) {
                                $oContrato->CTO_FECHA_INICIO = date("Y-m-d");
                            }
                            
                            // Detalle de servicios 
                            $validData = $validation->getValidData();
                            
                            // Si tiene servicio de agua
                            if(count($postData['servicios']) == 2 || $postData['servicios'][0] == 1){
                                if($validData['aguaFechaInstalacion'] != '1501-01-01'){$oContrato->CTO_AGU_FEC_INS = $validData['aguaFechaInstalacion'];}
                                if($validData['aguaConexionCaracteristica'] != -1){$oContrato->CTO_AGU_CAR_CNX = $validData['aguaConexionCaracteristica'];}
                                if($validData['aguaConexionDiametro'] != -1){$oContrato->CTO_AGU_DTO_CNX = $validData['aguaConexionDiametro'];}
                                if($validData['aguaDiametroRed'] != -1){$oContrato->CTO_AGU_DTO_RED = $validData['aguaDiametroRed'];}
                                if($validData['aguaMaterialConexion'] != -1){$oContrato->CTO_AGU_MAT_CNX = $validData['aguaMaterialConexion'];}
                                if($validData['aguaMaterialAbrazadera'] != -1){$oContrato->CTO_AGU_MAT_ABA = $validData['aguaMaterialAbrazadera'];}
                                if($validData['aguaUbicacionCaja'] != -1){$oContrato->CTO_AGU_UBI_CAJ = $validData['aguaUbicacionCaja'];}
                                if($validData['aguaMaterialCaja'] != -1){$oContrato->CTO_AGU_MAT_CAJ = $validData['aguaMaterialCaja'];}
                                if($validData['aguaEstadoCaja'] != -1){$oContrato->CTO_AGU_EST_CAJ = $validData['aguaEstadoCaja'];}
                                if($validData['aguaMaterialTapa'] != -1){$oContrato->CTO_AGU_MAT_TAP = $validData['aguaMaterialTapa'];}
                                if($validData['aguaEstadoTapa'] != -1){$oContrato->CTO_AGU_EST_TAP = $validData['aguaEstadoTapa'];}
                            }
                            
                            // Si tiene servicio de alcantarillado
                            if(count($postData['servicios']) == 2 || $postData['servicios'][0] == 2){
                                if($validData['alcFechaConexion'] != '1501-01-01'){$oContrato->CTO_ALC_FEC_INS = $validData['alcFechaConexion'];}
                                if($validData['alcConexionCaracteristica'] != -1){$oContrato->CTO_ALC_CAR_CNX = $validData['alcConexionCaracteristica'];}
                                if($validData['alcTipoConexion'] != -1){$oContrato->CTO_ALC_TIP_CNX = $validData['alcTipoConexion'];}
                                if($validData['alcConexionDiametro'] != -1){$oContrato->CTO_ALC_DTO_CNX = $validData['alcConexionDiametro'];}
                                if($validData['alcDiametroRed'] != -1){$oContrato->CTO_ALC_DTO_RED = $validData['alcDiametroRed'];}
                                if($validData['alcMaterialConexion'] != -1){$oContrato->CTO_ALC_MAT_CNX = $validData['alcMaterialConexion'];}
                                if($validData['alcUbicacionCaja'] != -1){$oContrato->CTO_ALC_UBI_CAJ = $validData['alcUbicacionCaja'];}
                                if($validData['alcMaterialCaja'] != -1){$oContrato->CTO_ALC_MAT_CAJ = $validData['alcMaterialCaja'];}
                                if($validData['alcEstadoCaja'] != -1){$oContrato->CTO_ALC_EST_CAJ = $validData['alcEstadoCaja'];}
                                if($validData['alcDimencionCaja'] != -1){$oContrato->CTO_ALC_DIM_CAJ = $validData['alcDimencionCaja'];}
                                if($validData['alcMaterialTapa'] != -1){$oContrato->CTO_ALC_MAT_TAP = $validData['alcMaterialTapa'];}
                                if($validData['alcEstadoTapa'] != -1){$oContrato->CTO_ALC_EST_TAP = $validData['alcEstadoTapa'];}
                                if($validData['alcMedidasTapa'] != -1){$oContrato->CTO_ALC_MED_TAP = $validData['alcMedidasTapa'];}
                            }
                            
                            $oContrato->save();
                            
                            foreach ($postData['servicios'] as $servicio) {
                                $oServicioContrato = new ServicioContrato();
                                $oServicioContrato->CTO_CODIGO = $oContrato->CTO_CODIGO;
                                $oServicioContrato->SRV_CODIGO = $servicio;
                                $oServicioContrato->save();
                            }
                        });
                            
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                            
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no resgistrado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el contrato'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por no encontrar contrato existente en tramite o activo
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['El predio tiene un contrato en tramite o activo'];
                    $this->respuestaData['formNuevoContrato'] = $postData;
                }
                
            }else{
                // Controlando errores de validación por no encontrar predio
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['No se encontró el código de predio solicitado'];
                $this->respuestaData['formNuevoContrato'] = $postData;
            }
        }
        
        // Asignar servicios a la data a enviar
        $this->respuestaData['servicios'] = Servicio::all()->toArray();
        // Asignar tipos de uso de predio a la data a enviar
        $this->respuestaData['tiposUsoPredio'] = TipoUsoPredio::all()->toArray();

        return $this->renderHTML($this->contratoViewsPath.'contratoNew');
    }
    
    /**
     * Método muestra lista de contratos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListContratos(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Contrato::count();
        $pagination = Contrato::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['contratos'] = Contrato::offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('CTO_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['contratos'] = [];
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoList');
    }
    
    
    /**
     * Método muestra lista de contratos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListContratos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar contratos
        $contrato = Contrato::join('PREDIO', 'CONTRATO.PRE_CODIGO', '=', 'PREDIO.PRE_CODIGO')
                    ->join('CLIENTE', 'PREDIO.CLI_CODIGO', '=', 'CLIENTE.CLI_CODIGO')
                    ->select('CONTRATO.*', 'PREDIO.*', 'CLIENTE.CLI_DOCUMENTO');
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $contrato = $contrato->where('CTO_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterPredio']) && $queryData['filterPredio'] != ''){
            $contrato = $contrato->where('CONTRATO.PRE_CODIGO', '=', $queryData['filterPredio']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $contrato = $contrato->where('CTO_ESTADO', '=', $queryData['filterEstado']);
        }
        if(isset($queryData['filterFechaTramite']) && $queryData['filterFechaTramite'] != '' && validateDate($queryData['filterFechaTramite'])) {
            $contrato = $contrato->whereDate('CTO_FECHA_TRAMITE', '=', $queryData['filterFechaTramite']);
        }
        if(isset($queryData['filterCliente']) && $queryData['filterCliente'] != ''){
           $contrato = $contrato->where('CLIENTE.CLI_NOMBRES', 'LIKE', '%'. $queryData['filterCliente'].'%');
        // if(isset($queryData['filterCliente']) && $queryData['filterCliente'] != ''){
           // $contrato = $contrato->where('CLIENTE.CLI_DOCUMENTO', '=', $queryData['filterCliente']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $contrato->count();
        $pagination = Contrato::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListContratos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['contratos'] = $contrato->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('CTO_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['contratos'] = [];
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoList');
    }
    
    /**
     * Método muestra todos los datos de contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailContrato(ServerRequest $request)
    {
        $codeContrato = (int) $request->getAttribute('contratoId');
        
        $contrato = Contrato::find($codeContrato);
        
        if($contrato != null){
            $this->respuestaData['contrato'] = $contrato->toArray();
            $this->respuestaData['servicios'] = $contrato->servicios->toArray();
            $tipoUsoPredio = $contrato->tipoUsoPredio;
            $this->respuestaData['tipoUsoPredio'] = $tipoUsoPredio->toArray();
            $this->respuestaData['tipoPredio'] = $tipoUsoPredio->tipoPredio->toArray();
            $predio = $contrato->predio;
            $this->respuestaData['predio'] = $predio->toArray();
            $this->respuestaData['calle'] = $predio->calle->toArray();
            $this->respuestaData['cliente'] = $predio->cliente->toArray();
            
            
            // Agregando los financiamientos activos a la data a enviar
            $this->respuestaData['financiamientos'] = Financiamiento::where('CTO_CODIGO', '=', $contrato->CTO_CODIGO)
                                                                        ->where('FTO_ESTADO', '=', 2)
                                                                        ->whereIn('FTO_CODIGO', function($query){
                                                                            $query->select('FTO_CODIGO')->from('FINANC_CUOTA')
                                                                                    ->where('FCU_ESTADO', '=', 1);})
                                                                        ->get()->toArray();
                                                                                    
            // Agregando los proyectos activos a la data a enviar
            $this->respuestaData['proyectos'] = Proyecto::where('PTO_ESTADO', '=', 2)
                                                            ->whereIn('PTO_CODIGO', function($query) use ($contrato){
                                                                $query->select('PTO_CODIGO')->from('CUOTA_EXTRAORDINARIA')
                                                                        ->where('CTO_CODIGO', '=', $contrato->CTO_CODIGO)
                                                                        ->where('CUE_ESTADO', '=', 1);})
                                                            ->get()->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el contrato'];
            $this->respuestaData['contratoId'] = $codeContrato;
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }
    
    
    /**
     * Método muestra todos los datos de contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\JsonResponse
     */
    public function getDetailContratoJson(ServerRequest $request)
    {
        $codeContrato = (int) $request->getAttribute('contratoId');
        
        $contrato = Contrato::find($codeContrato);
        
        if($contrato != null){
            $predio = $contrato->predio;
            $this->respuestaData['predio']['codigo'] = $predio->PRE_CODIGO;
            $this->respuestaData['predio']['direccion'] = $predio->PRE_DIRECCION;
            $this->respuestaData['calle']['nombre'] = $predio->calle->CAL_NOMBRE;
            $cliente = $predio->cliente;
            $this->respuestaData['cliente']['documento'] = $cliente->CLI_DOCUMENTO;
            $this->respuestaData['cliente']['nombre'] = $cliente->CLI_NOMBRES;
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el contrato solicitado'];
            $this->respuestaData['contratoId'] = $codeContrato;
        }
        
        return $this->responseJSON();
    }
    
    
    /**
     * Método muestra la vista para editar datos de contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditContrato(ServerRequest $request) {
        
        $codeContrato = (int) $request->getAttribute('contratoId');
        
        $oContrato = Contrato::find($codeContrato);
        
        if($oContrato != null){
            
            // Si el contrato esta anulado no se mostrarán los datos a editar
            if ($oContrato->CTO_ESTADO != 2) {
                $this->respuestaData['contrato'] = $oContrato->toArray();
                $predio = $oContrato->predio;
                $this->respuestaData['predio'] = $predio->toArray();
                $this->respuestaData['calle'] = $predio->calle->toArray();
                $this->respuestaData['cliente'] = $predio->cliente->toArray();
                $this->respuestaData['tipoUsoPredio'] = $oContrato->tipoUsoPredio->toArray();
                $this->respuestaData['serviciosActivos'] = $oContrato->servicios->toArray();
                
            }else{
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validacion';
                $this->respuestaEstadoDetalle = ['El contrato solicitado no puede ser editado poque se ecuentra anulado'];
                $this->respuestaData['contratoId'] = $codeContrato;
            }
            
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el contrato solicitado'];
            $this->respuestaData['contratoId'] = $codeContrato;
        }
        
        // Asignar servicios a la data a enviar
        $this->respuestaData['servicios'] = Servicio::all()->toArray();
        // Asignar tipos de uso de predio a la data a enviar
        $this->respuestaData['tiposUsoPredio'] = TipoUsoPredio::all()->toArray();
        
        return $this->renderHTML($this->contratoViewsPath.'contratoEdit');
    }
    
    
    /**
     * Método para actualizar datos de contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateContratoData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarContrato'] = $postData;
        } else {
            
            // Contrato a editar
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){
                
                // Los contratos solo se podrán modificar si no estan anulados
                if ($oContrato->CTO_ESTADO != 2) {
                        
                    try{
                        $oContrato->CTO_OBSERVACION = $postData['observacion'];
                        
                        // Si el contrato esta en tramite se verificara el cambio a estar a activo
                        if ($oContrato->CTO_ESTADO == 0 || $oContrato->CTO_ESTADO == 5 && isset($postData['estadoNuevo']) && $postData['estadoNuevo'] == 1) {
                            $oContrato->CTO_ESTADO = 1;
                            $oContrato->CTO_FECHA_INICIO = $postData['fechaInicio'];
                        }
                        
                        // Detalle de servicios
                        $servicios = $oContrato->servicios->toArray();
                        
                        // Si tiene servicio de agua
                        if(count($servicios) == 2 || $servicios[0]['SRV_CODIGO'] == 1){
                            if(isset($postData['aguaFechaInstalacion'])){
                                $oContrato->CTO_AGU_FEC_INS = ($postData['aguaFechaInstalacion'] == "") ? NULL : $postData['aguaFechaInstalacion'];}
                            
                            if(isset($postData['aguaConexionCaracteristica']) && $postData['aguaConexionCaracteristica'] != -1){
                                $oContrato->CTO_AGU_CAR_CNX = $postData['aguaConexionCaracteristica'];}
                            if(isset($postData['aguaConexionDiametro']) && $postData['aguaConexionDiametro'] != -1){
                                $oContrato->CTO_AGU_DTO_CNX = $postData['aguaConexionDiametro'];}
                            if(isset($postData['aguaDiametroRed']) && $postData['aguaDiametroRed'] != -1){
                                $oContrato->CTO_AGU_DTO_RED = $postData['aguaDiametroRed'];}
                            if(isset($postData['aguaMaterialConexion']) && $postData['aguaMaterialConexion'] != -1){
                                $oContrato->CTO_AGU_MAT_CNX = $postData['aguaMaterialConexion'];}
                            if(isset($postData['aguaMaterialAbrazadera']) && $postData['aguaMaterialAbrazadera'] != -1){
                                $oContrato->CTO_AGU_MAT_ABA = $postData['aguaMaterialAbrazadera'];}
                            
                            if(isset($postData['aguaUbicacionCaja'])){
                                $oContrato->CTO_AGU_UBI_CAJ = ($postData['aguaUbicacionCaja'] == -1) ? "": $postData['aguaUbicacionCaja'];}
                            if(isset($postData['aguaMaterialCaja'])){
                                $oContrato->CTO_AGU_MAT_CAJ = ($postData['aguaMaterialCaja'] == -1) ? "": $postData['aguaMaterialCaja'];}
                            if(isset($postData['aguaEstadoCaja'])){
                                $oContrato->CTO_AGU_EST_CAJ = ($postData['aguaEstadoCaja'] == -1) ? "": $postData['aguaEstadoCaja'];}
                            if(isset($postData['aguaMaterialTapa'])){
                                $oContrato->CTO_AGU_MAT_TAP = ($postData['aguaMaterialTapa'] == -1) ? "": $postData['aguaMaterialTapa'];}
                            if(isset($postData['aguaEstadoTapa'])){
                                $oContrato->CTO_AGU_EST_TAP = ($postData['aguaEstadoTapa'] == -1) ? "": $postData['aguaEstadoTapa'];}
                        }
                        
                        // Si tiene servicio de alcantarillado
                        if(count($servicios) == 2 || $servicios[0]['SRV_CODIGO'] == 2){
                            if(isset($postData['alcFechaConexion'])){
                                $oContrato->CTO_ALC_FEC_INS = ($postData['alcFechaConexion'] == "") ? NULL : $postData['alcFechaConexion'];}
                            
                            if(isset($postData['alcConexionCaracteristica']) && $postData['alcConexionCaracteristica'] != -1){
                                $oContrato->CTO_ALC_CAR_CNX = $postData['alcConexionCaracteristica'];}
                            if(isset($postData['alcTipoConexion']) && $postData['alcTipoConexion'] != -1){
                                $oContrato->CTO_ALC_TIP_CNX = $postData['alcTipoConexion'];}
                            if(isset($postData['alcConexionDiametro']) && $postData['alcConexionDiametro'] != -1){
                                $oContrato->CTO_ALC_DTO_CNX = $postData['alcConexionDiametro'];}
                            if(isset($postData['alcDiametroRed']) && $postData['alcDiametroRed'] != -1){
                                $oContrato->CTO_ALC_DTO_RED = $postData['alcDiametroRed'];}
                            if(isset($postData['alcMaterialConexion']) && $postData['alcMaterialConexion'] != -1){
                                $oContrato->CTO_ALC_MAT_CNX = $postData['alcMaterialConexion'];}
                            
                            if(isset($postData['alcUbicacionCaja'])){
                                $oContrato->CTO_ALC_UBI_CAJ = ($postData['alcUbicacionCaja'] == -1) ? "": $postData['alcUbicacionCaja'];}
                            if(isset($postData['alcMaterialCaja'])){
                                $oContrato->CTO_ALC_MAT_CAJ = ($postData['alcMaterialCaja'] == -1) ? "": $postData['alcMaterialCaja'];}
                            if(isset($postData['alcEstadoCaja'])){
                                $oContrato->CTO_ALC_EST_CAJ = ($postData['alcEstadoCaja'] == -1) ? "": $postData['alcEstadoCaja'];}
                            if(isset($postData['alcDimencionCaja'])){
                                $oContrato->CTO_ALC_DIM_CAJ = ($postData['alcDimencionCaja'] == -1) ? "": $postData['alcDimencionCaja'];}
                            if(isset($postData['alcMaterialTapa'])){
                                $oContrato->CTO_ALC_MAT_TAP = ($postData['alcMaterialTapa'] == -1) ? "": $postData['alcMaterialTapa'];}
                            if(isset($postData['alcEstadoTapa'])){
                                $oContrato->CTO_ALC_EST_TAP = ($postData['alcEstadoTapa'] == -1) ? "": $postData['alcEstadoTapa'];}
                            if(isset($postData['alcMedidasTapa'])){
                                $oContrato->CTO_ALC_MED_TAP = ($postData['alcMedidasTapa'] == -1) ? "": $postData['alcMedidasTapa'];}
                        }
                        
                        // Actualizar tipo uso de predio
                        $tipoUsoPredio = $oContrato->tipoUsoPredio->toArray();
                        if ($tipoUsoPredio['TUP_CODIGO'] != $postData['tipoUsoPredio']) {
                            $oContrato->TUP_CODIGO = $postData['tipoUsoPredio'];
                        }
                        
                        // Guardar cambios en el objeto contrato
                        $oContrato->save();
                        
                        
                        // Actualizando servicios - Agregando servicios nuevos
                        foreach ($postData['servicios'] as $servicioRqst) {
                            $existServicio = false;
                            
                            foreach ($servicios as $servicio){
                                if ($servicio['SRV_CODIGO'] == $servicioRqst) {
                                    $existServicio = true;
                                    break;
                                }
                            }
                            
                            if(!$existServicio){
                                $oServicioContrato = new ServicioContrato();
                                $oServicioContrato->CTO_CODIGO = $oContrato->CTO_CODIGO;
                                $oServicioContrato->SRV_CODIGO = $servicioRqst;
                                $oServicioContrato->save();
                            }
                        }
                        
                        // Actualizando servicios - Quitando servicios eliminados
                        foreach ($servicios as $servicio) {
                            $existServicio = false;
                            
                            foreach ($postData['servicios'] as $servicioRqst){
                                if ($servicio['SRV_CODIGO'] == $servicioRqst) {
                                    $existServicio = true;
                                    break;
                                }
                            }
                            
                            if(!$existServicio){
                                $oServicioContrato = ServicioContrato::where('CTO_CODIGO', '=', $servicio['pivot']['CTO_CODIGO'])
                                                                            ->where('SRV_CODIGO', '=', $servicio['pivot']['SRV_CODIGO'])->first();
                                $oServicioContrato->delete();
                            }
                        }
                        
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de contrato'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación porque el contrato fue anulado
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['No se puede modificar el contrato porque fue anulado'];
                    $this->respuestaData['formEditarContrato'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar el contrato
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el contrato solicitado'];
                $this->respuestaData['formEditarContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoEdit');
    }
    
    /**
     * Método Anula un contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function annularContrato(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesAnnular($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formAnularContrato'] = $postData;
        } else {
            
            // Contrato a anular
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){
                    
                try{
                    
                    $oContrato->CTO_ESTADO = 2;
                    $oContrato->CTO_FECHA_ANULACION = date("Y-m-d");
                    $oContrato->save();
                    
                    // Redireccionamos a la vista detalle de contrato
                    return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Contrato no anulado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se anuló el contrato'];
                }
                
            }else{
                
                // Controlando error por no encontrar contrato a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el contrato'];
                $this->respuestaData['formAnularContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }

    /**
     * Método Corta o Suspende un contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function suspendContrato(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesSuspend($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formSuspenderContrato'] = $postData;
        } else {
            
            // Contrato a suspender
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){
                
                if ($oContrato->CTO_ESTADO == 1) {

                    try{
                    
                        $oContrato->CTO_ESTADO = 3;
                        $oContrato->CTO_FECHA_SUSPENCION = date("Y-m-d");
                        $oContrato->save();
                        
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no suspendido. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se suspendió el contrato'];
                    } 
                }else {
                    // Controlando error (solo se suspenderá el contrato si está activo)
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'Error de validación';
                    $this->respuestaEstadoDetalle = ['Solo se suspenderá el contrato si el estado es igual a activo'];
                    $this->respuestaData['formSuspenderContrato'] = $postData;
                }
                
            }else{
                
                // Controlando error por no encontrar contrato a suspender
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el contrato'];
                $this->respuestaData['formSuspenderContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }


    /**
     * Método Reconectar un contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function reconnectContrato(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesReconnect($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formReconectarContrato'] = $postData;
        } else {
            
            // Contrato a reconectar
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){

                if ($oContrato->CTO_ESTADO == 3) {
                    try{
                    
                        $oContrato->CTO_ESTADO = 1;
                        $oContrato->CTO_FECHA_RECONECCION = date("Y-m-d");
                        $oContrato->save();
                        
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no reconectado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se reconectó el contrato'];
                    }
                }else{
                    
                    // Controlando error (solo se reactivará el contrato si está suspendido)
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'Error de validación';
                    $this->respuestaEstadoDetalle = ['Solo se reactivará el contrato si el estado es igual a suspendido'];
                    $this->respuestaData['formReconectarContrato'] = $postData;
                }
                
                
            }else{
                
                // Controlando error por no encontrar contrato a reconectar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró el contrato'];
                $this->respuestaData['formReconectarContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }

    /**
     * Método Inicio de Mantenimiento en contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function onMaintenanceContrato(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesOnMaintenance($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formMantenimientoContrato'] = $postData;
        } else {
            
            // Contrato a suspender
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){
                
                if ($oContrato->CTO_ESTADO == 1) {

                    try{
                    
                        $oContrato->CTO_ESTADO = 4;
                        $oContrato->CTO_FECHA_INICIO_MANTENIMIENTO = date("Y-m-d");
                        $oContrato->save();
                        
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no en Mantenimiento. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se cambió en Mantenimiento el contrato'];
                    } 
                }else {
                    // Controlando error (solo se pondrá en mantenimiento el contrato si está activo)
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'Error de validación';
                    $this->respuestaEstadoDetalle = ['Solo se cambiará el contrato si el estado es igual a activo'];
                    $this->respuestaData['formMantenimientoContrato'] = $postData;
                }
                
            }else{
                
                // Controlando error por no encontrar contrato a mantenimiento
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el contrato'];
                $this->respuestaData['formMantenimientoContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }


    /**
     * Método Fin de Mantenimiento en contrato
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function endMaintenanceContrato(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oContratoValidation = new ContratoValidation();
        $validation = $oContratoValidation->veryRulesEndMaintenance($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formFinMantenimientoContrato'] = $postData;
        } else {
            
            // Contrato a reconectar
            $oContrato = Contrato::find($postData['codigo']);
            
            if(!is_null($oContrato)){

                if ($oContrato->CTO_ESTADO == 4) {
                    try{
                    
                        $oContrato->CTO_ESTADO = 1;
                        $oContrato->CTO_FECHA_FIN_MANTENIMIENTO = date("Y-m-d");
                        $oContrato->save();
                        
                        // Redireccionamos a la vista detalle de contrato
                        return new RedirectResponse('/contrato/detalle/'.$oContrato->CTO_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Contrato no activo. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se activó el contrato'];
                    }
                }else{
                    
                    // Controlando error (solo se activará el contrato si está en mantenimiento)
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'Error de validación';
                    $this->respuestaEstadoDetalle = ['Solo se activará el contrato si el estado es igual en mantenimiento'];
                    $this->respuestaData['formFinMantenimientoContrato'] = $postData;
                }
                
                
            }else{
                
                // Controlando error por no encontrar contrato a activar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontró el contrato'];
                $this->respuestaData['formFinMantenimientoContrato'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->contratoViewsPath.'contratoDetail');
    }
    
}
  