<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Models\Recibo;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use App\Libraries\LogMonolog;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Sector;


class ReciboController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/recibo/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para generar recibos
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewRecibos()
    {
        // Agregando los meses a la data a enviar
        $mesAnterior = (date("m") - 1 == 0)? "12" : date("m") - 1;
        $this->respuestaData['mes'] = getMonthNameFromNumber(str_pad($mesAnterior, 2, "0", STR_PAD_LEFT));
        // Agregando los años a la data a enviar
        $this->respuestaData['year'] = date('Y', strtotime("-1 month"));
        
        return $this->renderHTML($this->viewsPath.'reciboNews');
    }
    

    /**
     * Método genera nuevos recibos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewRecibos(ServerRequest $request){

        // Los recibos sólo pueden generarse desde el día primero hasta el diesiocho de cada mes
        $dia = date("d");
        if($dia >= 1 && $dia < 19){
                
            try{
                
                $fecha = date("Y-m-d", strtotime("-1 month"));
                $pdo = Capsule::connection()->getPdo();
                $stmt=$pdo->prepare('CALL sp_generar_recibos_mensual(?, @code_rpta)');
                $stmt->execute([$fecha]);
                $codeRptaBD = Capsule::select("SELECT @code_rpta AS RESPUESTA");
                
                switch ($codeRptaBD[0]->RESPUESTA) {
                    case 200:
                        $msj = "Recibos facturados exitosamente";
                        break;
                    case 400:
                        $msj = "Recibos no facturados: Periodo existente";
                        break;
                    case 500:
                        $msj = "Recibos no facturados: Ocurrió un error inesperado";
                        break;
                }
                
                // Enviando respuesta a la vista
                $this->respuestaCodigo = $codeRptaBD[0]->RESPUESTA;
                $this->respuestaEstadoDetalle = [$msj];
                
            }catch(PDOException $e){
                $this->loggerfile->debug('Recibos no generados. Mensaje:'.$e->getMessage());
                $this->respuestaCodigo = 500;
                $this->respuestaEstado = 'error de servidor';
                $this->respuestaEstadoDetalle = ['Recibos no facturados: Ocurrió un error inesperado'];
            }
            
        }else{
            
            // Controlando error por no encontrar sector
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validación';
            $this->respuestaEstadoDetalle = ['Los recibos sólo pueden generarse desde el día primero hasta el diesiocho de cada mes'];
        }
        
        return $this->responseJSON();
    }
    
    /**
     * Método muestra lista de recibos
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListRecibos(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) Recibo::count();
        $pagination = Recibo::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['recibos'] = Recibo::offset($pagination['paginaOffset'])
                                                        ->limit($pagination['paginaLimit'])
                                                        ->orderBy('RBO_CODIGO', 'desc')
                                                        ->get()->toArray();
        }else{
            $this->respuestaData['recibos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'reciboList');
    }
    
    
    /**
     * Método muestra lista de recibos según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListRecibos(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar recibos
        $recibo = Recibo::join('CONTRATO', 'RECIBO.CTO_CODIGO', '=', 'CONTRATO.CTO_CODIGO')
                            ->join('PREDIO', 'CONTRATO.PRE_CODIGO', '=', 'PREDIO.PRE_CODIGO')
                            ->join('CLIENTE', 'PREDIO.CLI_CODIGO', '=', 'CLIENTE.CLI_CODIGO')
                            ->select('RECIBO.*', 'CLIENTE.CLI_DOCUMENTO');
        
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $recibo = $recibo->where('RBO_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterContrato']) && $queryData['filterContrato'] != ''){
            $recibo = $recibo->where('RECIBO.CTO_CODIGO', '=', $queryData['filterContrato']);
        }
        if(isset($queryData['filterPeriodo']) && $queryData['filterPeriodo'] != ''){
            $recibo = $recibo->where('RBO_PERIODO', '=', $queryData['filterPeriodo']);
        }
        if(isset($queryData['filterFechaEmision']) && $queryData['filterFechaEmision'] != '' && validateDate($queryData['filterFechaEmision'])) {
            $recibo = $recibo->whereDate('RBO_CREATED', '=', $queryData['filterFechaEmision']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != '' && $queryData['filterEstado'] != -1){
            $recibo = $recibo->where('RBO_ESTADO', '=', $queryData['filterEstado']);
        }
        if(isset($queryData['filterFechaCorte']) && $queryData['filterFechaCorte'] != '' && validateDate($queryData['filterFechaCorte'])) {
            $recibo = $recibo->whereDate('RBO_FECHA_CORTE', '=', $queryData['filterFechaCorte']);
        }
        if(isset($queryData['filterCliente']) && $queryData['filterCliente'] != ''){
            $recibo = $recibo->where('CLIENTE.CLI_DOCUMENTO', '=', $queryData['filterCliente']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual'])
                                && $queryData['filterPaginaActual'] != ''
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
        $cantidadRegistros = $recibo->count();
        $pagination = Recibo::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListRecibos'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['recibos'] = $recibo->offset($pagination['paginaOffset'])
                                                            ->limit($pagination['paginaLimit'])
                                                            ->orderBy('RBO_CODIGO', 'desc')
                                                            ->get()->toArray();
        }else{
            $this->respuestaData['recibos'] = [];
        }
        
        return $this->renderHTML($this->viewsPath.'reciboList');
    }
    
    
    /**
     * Método muestra recibos por periodo
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformImpresionMasiva()
    {
        // Asignar las sectores a la data a enviar
        $this->respuestaData['sectores'] = Sector::all()->toArray();
        
        return $this->renderHTML($this->viewsPath.'reciboImpresionMasiva');
    }
    
    /**
     * Método verifica y actualizar los recibos vencidos
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function checkExpired()
    {
        
        try{
            
            $pdo = Capsule::connection()->getPdo();
            $stmt=$pdo->prepare('CALL sp_actualiza_rbos_vencidos(@msj)');
            $stmt->execute();
            $codeRptaBD = Capsule::select("SELECT @msj AS RESPUESTA");
            
            switch ($codeRptaBD[0]->RESPUESTA) {
                case 200:
                    $msj = "Estado de recibos actulaizados";
                    break;
                case 500:
                    $msj = "Recibos no actualizados: Ocurrió un error inesperado";
                    break;
            }
            
            // Enviando respuesta a la vista
            $this->respuestaCodigo = $codeRptaBD[0]->RESPUESTA;
            $this->respuestaEstadoDetalle = [$msj];
            
        }catch(PDOException $e){
            $this->loggerfile->debug('Recibos no actualizados. Mensaje:'.$e->getMessage());
            $this->respuestaCodigo = 500;
            $this->respuestaEstado = 'error de servidor';
            $this->respuestaEstadoDetalle = ['Recibos no actualizados: Ocurrió un error inesperado'];
        }
        
        return $this->responseJSON();
    }
    
}
  