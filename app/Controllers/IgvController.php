<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use Laminas\Diactoros\ServerRequest;
use App\Libraries\LogMonolog;
use App\Models\Igv;
use App\Validation\IgvValidation;
use Laminas\Diactoros\Response\RedirectResponse;

class IgvController extends BaseController{
    
    private $loggerfile;
    private $viewsPath = '/administration/igv/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    
    
    /**
     * Método muestra la vista para editar datos de igv
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditIgv(ServerRequest $request) {
        
        $igv = Igv::find(1);
        
        if($igv!= null){
            $this->respuestaData['igv'] = $igv->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el igv para actualizar'];
        }
        
        return $this->renderHTML($this->viewsPath.'igvEdit');
    }
    
    
    /**
     * Método para actualizar datos de igv
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateIgvData(ServerRequest $request) {
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oIgvValidation = new IgvValidation();
        $validation = $oIgvValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarIgv'] = $postData;
        } else {
            
            // Igv a editar
            $oIgv = Igv::find(1);
            
            if(!is_null($oIgv)){
                
                try{
                    $oIgv->IGV_PORCENTAJE = $postData['igv'];
                    $oIgv->save();
                    
                    // Redireccionamos a la vista editar igv
                    return new RedirectResponse('/igv/editar');
                    
                }catch(\PDOException $e){
                    $this->loggerfile->debug('Igv no actualizado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de igv'];
                }
                
            }else{
                
                // Controlando error por no encontrar igv a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el igv a editar'];
                $this->respuestaData['formEditarIgv'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->viewsPath.'igvEdit');
    }
    
}
  