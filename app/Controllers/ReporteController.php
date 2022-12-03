<?php namespace App\Controllers;


use Laminas\Diactoros\ServerRequest;
use App\Libraries\InputFilter;
use App\Models\Egreso;
use App\Models\Recibo;
use Dompdf\Dompdf;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Ingreso;
use Luecano\NumeroALetras\NumeroALetras;
use App\Models\Sector;
use App\Validation\ReporteValidation;
use App\Models\Financiamiento;
use App\Models\User;
use App\Libraries\Session;

class ReporteController extends BaseController{
    
    private $viewsPath = '/administration/reporte/';
    
    /**
     * Método muestra recibo
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getRecibo(ServerRequest $request)
    {
        $reciboCodigo = (int) $request->getAttribute('reciboId');
        
        // Obteniendo recibo
        $recibo = Recibo::find($reciboCodigo);
        
        // Si encontramos el resgistro de recibo lo mostramos
        if(!is_null($recibo)){
            
            $queryReciboDatosRbo = "CALL sp_get_datos_rbo({$reciboCodigo})";
            $reciboDatos = Capsule::select($queryReciboDatosRbo);
            
            $queryReciboDetalleRbo = "CALL sp_get_detalles_rbo({$reciboCodigo})";
            $reciboDetalle = Capsule::select($queryReciboDetalleRbo);
            
            $queryReciboOtrosRbo = "CALL sp_get_otros_rbo({$reciboCodigo})";
            $reciboOtros = Capsule::select($queryReciboOtrosRbo);
            
            $this->respuestaData['reciboDatos'] = $reciboDatos;
            $this->respuestaData['reciboDetalle'] = $reciboDetalle;
            $this->respuestaData['reciboOtros'] = $reciboOtros;
            
            $response = $this->renderHTML($this->viewsPath."recibo");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            // Metodo para enviar file al navegador
            $dompdf->stream("{$recibo->RBO_CODIGO}_{$recibo->RBO_PERIODO}_recibo.pdf",array("Attachment" => false));
            
        }else{
            
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            return new \Laminas\Diactoros\Response\TextResponse('404 Recurso no encontrado');
        }
        
    }
    
    
    /**
     * Método muestra ticket de ingreso
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getTicketIngreso(ServerRequest $request)
    {
        $ingresoCodigo = (int) $request->getAttribute('ingresoId');
        
        // Obteniendo ingreso
        $ingreso = Ingreso::find($ingresoCodigo);
        
        // Si encontramos el resgistro de ingreso lo mostramos
        if(!is_null($ingreso)){
            
            $queryReciboDatosTicket = "CALL sp_get_datos_ticket({$ingreso->IGR_CODIGO}, '{$ingreso->IGR_TIPO}')";
            $ticketDatos = Capsule::select($queryReciboDatosTicket);
            $this->respuestaData['datosTicket'] = $ticketDatos;
            $this->respuestaData['ingresoTipo'] = $ingreso->IGR_TIPO;
            
            // Pasar cantidad de numeros a letras
            $formatter = new NumeroALetras();
            $oStdClass = $ticketDatos[0];
            $this->respuestaData['cantidadLetras'] = $formatter->toInvoice($oStdClass->CANTIDAD, 2, 'SOLES');
            
            $response = $this->renderHTML($this->viewsPath."ticketIngreso");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            // Metodo para enviar file al navegador
            $dompdf->stream("{$ingreso->IGR_CODIGO}_ingreso.pdf",array("Attachment" => false));
        }else{
            
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            return new \Laminas\Diactoros\Response\TextResponse('404 Recurso no encontrado');
        }
        
    }
    
    
    /**
     * Método muestra ticket de egreso
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getTicketEgreso(ServerRequest $request)
    {
        $egresoCodigo = (int) $request->getAttribute('egresoId');
        
        // Obteniendo egreso
        $egreso = Egreso::find($egresoCodigo);
        
        // Si encontramos el resgistro de egreso lo mostramos
        if(!is_null($egreso)){
            
            $queryReciboDatosTicket = "CALL sp_get_ticket_egreso({$egreso->EGR_CODIGO})";
            $ticketDatos = Capsule::select($queryReciboDatosTicket);
            $this->respuestaData['datosTicket'] = $ticketDatos;
            
            // Pasar cantidad de numeros a letras
            $formatter = new NumeroALetras();
            $oStdClass = $ticketDatos[0];
            $this->respuestaData['cantidadLetras'] = $formatter->toInvoice($oStdClass->CANTIDAD, 2, 'SOLES');
            
            $response = $this->renderHTML($this->viewsPath."ticketEgreso");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            // Metodo para enviar file al navegador
            $dompdf->stream("{$egreso->EGR_CODIGO}_egreso.pdf",array("Attachment" => false));
        }else{
            
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            return new \Laminas\Diactoros\Response\TextResponse('404 Recurso no encontrado');
        }
        
    }
    
    
    /**
     * Método muestra recibos por periodo y sector
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getRecibos(ServerRequest $request)
    {
        $month = $request->getAttribute('month');
        $year = $request->getAttribute('year');
        $sectorCodigo = (int) $request->getAttribute('sectorId');
        
        $attributesData = ['month' => $month, 'year' => $year, 'sector' => $sectorCodigo];
        $attributesData = arrayTrimSpaces($attributesData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $attributesData = $oInputFilter->process($attributesData);
        
        // Validar los datos recibidos
        $oReporteValidation = new ReporteValidation();
        $validation = $oReporteValidation->veryRulesGetRecibos($attributesData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formVerRecibosPorperiodo'] = $attributesData;
        } else {
            
            // Sector de busqueda
            $sector = Sector::find($sectorCodigo);
            if (!is_null($sector)) {
                
                // Asignar periodo de busqueda
                $periodo = trim($month)." - ".$year;
                
                $recibos = Capsule::table("SECTOR_CALLE")->join('CALLE', 'SECTOR_CALLE.CAL_CODIGO', '=', 'CALLE.CAL_CODIGO')
                                                            ->join('PREDIO', 'CALLE.CAL_CODIGO', '=', 'PREDIO.CAL_CODIGO')
                                                            ->join('CONTRATO', 'PREDIO.PRE_CODIGO', '=', 'CONTRATO.PRE_CODIGO')
                                                            ->join('RECIBO', 'CONTRATO.CTO_CODIGO', '=', 'RECIBO.CTO_CODIGO')
                                                            ->select('RECIBO.RBO_CODIGO')
                                                            ->where("SECTOR_CALLE.STR_CODIGO", "=", $sectorCodigo)
                                                            ->where("RBO_PERIODO","=", $periodo)->get()->toArray();
                
                // Si existen recibos con el periodo y sector solicitados, se imprimen
                if(!empty($recibos)){
                    
                    $arrayRecibos = [];
                    $contador = 0;
                    foreach ($recibos as $recibo) {
                        
                        $queryReciboDatosRbo = "CALL sp_get_datos_rbo({$recibo->RBO_CODIGO})";
                        $reciboDatos = Capsule::select($queryReciboDatosRbo);
                        
                        $queryReciboDetalleRbo = "CALL sp_get_detalles_rbo({$recibo->RBO_CODIGO})";
                        $reciboDetalle = Capsule::select($queryReciboDetalleRbo);
                        
                        $queryReciboOtrosRbo = "CALL sp_get_otros_rbo({$recibo->RBO_CODIGO})";
                        $reciboOtros = Capsule::select($queryReciboOtrosRbo);
                        
                        $arrayRecibos[$contador]['reciboDatos'] = $reciboDatos;
                        $arrayRecibos[$contador]['reciboDetalle'] = $reciboDetalle;
                        $arrayRecibos[$contador]['reciboOtros'] = $reciboOtros;
                        $contador++;
                    }
                    
                    // Enviando data a la vista recibos
                    $this->respuestaData['recibos'] = $arrayRecibos;
                    $this->respuestaData['cantidadRecibos'] = $contador;
                    
                    $response = $this->renderHTML($this->viewsPath."recibos");
                    
                    // Almacena en cache el HTML
                    ob_start();
                    echo $response->getBody();
                    $html = ob_get_clean();
                    
                    $dompdf = new Dompdf();
                    // Instancio opciones necesarias para incluir rutas locales y web
                    $options = $dompdf->getOptions();
                    $options->set(array('isRemoteEnabled' => true));
                    $dompdf->setOptions($options);
                    
                    // Carga el HTML
                    $dompdf->loadHtml($html);
                    $dompdf->render();
                    
                    // Metodo para enviar file al navegador
                    $dompdf->stream("recibos_{$sector->STR_NOMBRE}_{$periodo}.pdf",array("Attachment" => true));
                    
                }else{
                    
                    $this->respuestaCodigo = 404;
                    $this->respuestaEstado = 'recurso no encontrado';
                    $this->respuestaEstadoDetalle = ['No se encontró resultados'];
                    $this->respuestaData['formVerRecibosPorperiodo'] = $attributesData;
                }
            }else{
                
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['No se econtro el sector solicitado'];
                $this->respuestaData['formVerRecibosPorperiodo'] = $attributesData;
            }
        }
        
        // Asignar las sectores a la data a enviar
        $this->respuestaData['sectores'] = Sector::all()->toArray();
        return $this->renderHTML('/administration/recibo/reciboImpresionMasiva');
    }
    
    
    /**
     * Método muestra contrato de financiamiento
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getContratoFinanciamiento(ServerRequest $request)
    {
        $financiamientoCodigo = (int) $request->getAttribute('financiamientoId');
        
        // Obteniendo financiamiento
        $financiamiento = Financiamiento::find($financiamientoCodigo);
        
        // Si encontramos el resgistro de financiamiento lo mostramos
        if(!is_null($financiamiento)){
            
            // Obtener datos de cliente
            $queryFinanDatosCliente = "CALL sp_datos_usuario_financiado({$financiamiento->FTO_CODIGO})";
            $clienteDatos = Capsule::select($queryFinanDatosCliente);
            $this->respuestaData['clienteDatos'] = $clienteDatos;
            
            // Obtener datos de financiamiento
            $queryFinanDatos = "CALL sp_datos_financiamiento({$financiamiento->FTO_CODIGO})";
            $financiamientoDatos = Capsule::select($queryFinanDatos);
            $this->respuestaData['financiamientoDatos'] = $financiamientoDatos;
            
            if ($financiamiento->FTO_ESTADO == 2) {
                // Obtenemos recibos financiados
                $queryRF = "SELECT RBO_PERIODO AS PERIODO, RBO_MNTO_TOTAL AS TOTAL
                            FROM RECIBO WHERE FTO_CODIGO = {$financiamiento->FTO_CODIGO}";
                $recibosFinanciados = Capsule::select($queryRF);
                
                // Obtenemos cuotas de financiamientos
                $queryCF = "SELECT FCU_NUM_CUOTA AS CUOTA, DATE_FORMAT(FCU_FECHA_DE_CRONOGRAMA,'%d/%m/%Y') AS FECHA, FCU_MONTO_CUOTA AS MONTO,
                            (SELECT fc_get_importe_contrato({$financiamiento->CTO_CODIGO})) AS IMPORTE_CONSUMO FROM FINANC_CUOTA
                            WHERE FTO_CODIGO = {$financiamiento->FTO_CODIGO}";
                $cuotasFinanciamiento = Capsule::select($queryCF);
                
                // Agregamos datos abtenidos a la data a enviar
                $this->respuestaData['financiamiento'] = $financiamiento->toArray();
                $this->respuestaData['recibosFinanciados'] = $recibosFinanciados;
                $this->respuestaData['cuotasFinanciamiento'] = $cuotasFinanciamiento;
            }
            
            // Pasar cantidad de numeros a letras
            $formatter = new NumeroALetras();
            $this->respuestaData['deudaLetras'] = $formatter->toInvoice($financiamiento->FTO_DEUDA, 2, 'SOLES');
            
            $response = $this->renderHTML($this->viewsPath."contratoFinanciamiento");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            // Metodo para enviar file al navegador
            $dompdf->stream("{$financiamiento->FTO_CODIGO}_financiamiento.pdf",array("Attachment" => false));
        }else{
            
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            return new \Laminas\Diactoros\Response\TextResponse('404 Recurso no encontrado');
        }
        
    }
    
    
    /**
     * Método muestra formulario para generar arqueo diario
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormArqueoDiario()
    {
        return $this->renderHTML($this->viewsPath.'formArqueoDiario');
    }
    
    
    /**
     * Método para generar arqueo diario
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getArqueoDiario(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $attributesData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oReporteValidation = new ReporteValidation();
        $validation = $oReporteValidation->veryRulesGetArqueoDiario($attributesData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formArqueoDiario'] = $attributesData;
        } else {
                
            $queryIngresos = "CALL sp_get_ingresos('{$postData['date']}')";
            $ingresos = Capsule::select($queryIngresos);
            
            $queryEgresos = "CALL sp_get_egresos('{$postData['date']}')";
            $egresos = Capsule::select($queryEgresos);
            
            $usuario = User::find(Session::getUserValue('id'));
            
            // Enviando data a la vista recibos
            $this->respuestaData['ingresos'] = $ingresos;
            $this->respuestaData['egresos'] = $egresos;
            $this->respuestaData['fechaArqueo'] = $postData['date'];
            $this->respuestaData['usuario'] = $usuario->USU_NOMBRES . " " . $usuario->USU_APELLIDOS;
            
            $response = $this->renderHTML($this->viewsPath."arqueoDiario");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            
            // Metodo para enviar file al navegador
            $dompdf->stream("Arqueo_diario.pdf",array("Attachment" => true));
                
        }
        
        
        return $this->renderHTML('/administration/reporte/formArqueoDiario');
    }
    
    
    
    /**
     * Método muestra formulario para generar arqueo semanal
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormArqueoSemanal()
    {
        return $this->renderHTML($this->viewsPath.'formArqueoSemanal');
    }
    
    
    
    /**
     * Método para generar arqueo semanal
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getArqueoSemanal(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $attributesData = $oInputFilter->process($postData);
        
        // Validar los datos recibidos
        $oReporteValidation = new ReporteValidation();
        $validation = $oReporteValidation->veryRulesGetArqueoSemanal($attributesData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formArqueoSemanal'] = $attributesData;
        } else {
            
            $queryMontosArqueo = "CALL sp_montos_arqueo_semanal('{$postData['fechaInicio']}', '{$postData['fechaFin']}')";
            $montosArqueo = Capsule::select($queryMontosArqueo);
            
            $queryRegistrosArqueo = "CALL sp_registros_reporte_semanal('{$postData['fechaInicio']}', '{$postData['fechaFin']}')";
            $registrosArqueo = Capsule::select($queryRegistrosArqueo);
            
            $usuario = User::find(Session::getUserValue('id'));
            
            // Enviando data a la vista recibos
            $this->respuestaData['montosArqueo'] = $montosArqueo;
            $this->respuestaData['registrosArqueo'] = $registrosArqueo;
            $this->respuestaData['fechaInicio'] = $postData['fechaInicio'];
            $this->respuestaData['fechaFin'] = $postData['fechaFin'];
            $this->respuestaData['usuario'] = $usuario->USU_NOMBRES . " " . $usuario->USU_APELLIDOS;
            
            $response = $this->renderHTML($this->viewsPath."arqueoSemanal");
            
            // Almacena en cache el HTML
            ob_start();
            echo $response->getBody();
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            // Instancio opciones necesarias para incluir rutas locales y web
            $options = $dompdf->getOptions();
            $options->set(array('isRemoteEnabled' => true));
            $dompdf->setOptions($options);
            
            // Carga el HTML
            $dompdf->loadHtml($html);
            $dompdf->render();
            
            // Metodo para enviar file al navegador
            $dompdf->stream("Arqueo_semanal.pdf",array("Attachment" => true));
            
        }
        
        
        return $this->renderHTML('/administration/reporte/formArqueoSemanal');
    }
    
}