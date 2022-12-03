<?php  namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

class BaseController {
    
    // Definicion de parametros de respuesta
    protected $respuestaCodigo = 200;       // Codigo de respuesta
    protected $respuestaEstado = 'ok';      // Breve descripción del estado de respuesta
    protected $respuestaEstadoDetalle = []; // Array de mensajes propios de la solicitud
    protected $respuestaData = [];          // Array de datos de respuesta
    protected $respuestaHeaders = [];       // Array de cabezeras de respuesta
    
    // Pagina a renderizar
    protected $pageToRender = "";  
    
    
    /**
     * Método configura el entorno de twig
     * @return \Twig\Environment
     */
    private function confTwig(){
        $loader = new FilesystemLoader(APP_PATH.'/resources/views');
        $twig = new Environment($loader, [
            //'cache' => APP_PATH.'/resources/views/compiled_views',
            //'auto_reload' => true,
            'strict_variables' => true
        ]);
        $twig->addGlobal('PUBLIC_PATH', getenv('PUBLIC_PATH'));
        $twig->addGlobal('SITE_NAME', getenv('SITE_NAME'));
        $twig->addGlobal('SESSION', $_SESSION);
        
        return $twig;
    }
    
    /**
     * Método para renderizar pagina twig.
     * En caso de no recibir parametros, se cargará la pagina asignada a "$pageToRender"
     * @param string nombre de la pagina a cargar sin extensión
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    protected function renderHTML(string $fileView = null){
        
        $variables = $this->getResponseVariables();
        $twig = $this->confTwig();
        
        if(is_null($fileView)){
            $fileView = $this->pageToRender;
        }
        
        return new HtmlResponse($twig->render($fileView.'.twig', $variables), $this->respuestaCodigo, $this->respuestaHeaders);
    }
    
    /**
     * Método para enviar respuesta JSON
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    protected function responseJSON(){
        
        $variables = $this->getResponseVariables();
        
        return new JsonResponse($variables, $this->respuestaCodigo, $this->respuestaHeaders);
    }
    
    
    /**
     * Método para obtener las variables de respuesta de solicitud
     * @return array
     */
    protected function getResponseVariables() {
        
        $variables = [];
        $variables['codigo'] = $this->respuestaCodigo;
        $variables['estado'] = $this->respuestaEstado;
        $variables['estadoDetalle'] = $this->respuestaEstadoDetalle;
        $variables['data'] = $this->respuestaData;
        
        return $variables;
    }
    
}
