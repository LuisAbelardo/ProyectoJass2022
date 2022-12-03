<?php

use Aura\Router\RouterContainer;
use Laminas\Diactoros\ServerRequestFactory;
use App\Libraries\Session;
use App\Libraries\LogMonolog;

// Cargar Composer
include_once '../vendor/autoload.php';

// Inicializar Sesión
Session::start();

// Ajustar la hora a Lima/Perú (Esto no puede ser util para registrar logs)
date_default_timezone_set('America/Lima'); 

// Registrar escritor de log
LogMonolog::logFile();

// Cargar datos del archivo .env => vlucas/phpdotenv 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


// Cargar archivos de configuración
require_once __DIR__.'/../config/app.php';


// Crear un request con las variables globales => laminas-diactoros
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);


// Crear un contenedor de rutas => aura-router
$routerContainer = new RouterContainer();
// Obtener objeto mapa de rutas
$map = $routerContainer->getMap();
// Agregar URLs al objeto mapa de rutas
$map = routes($map);
// Objeto para buscar la ruta solicitada a partir del request
$matcher = $routerContainer->getMatcher();
// Obtener ruta a partir del request
$route = $matcher->match($request);


// Si la ruta no existe, asignamos el controlador y método
// predefinido para atender tal indicencia
if(!$route){
    $controllerName = '\\App\\Controllers\\IncidenciasController';
    $actionName = 'recursoNoEncontrado';
    
}else{

    // Obtenemos el controlador de la ruta
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    
    // Obtenemos información sobre acceso a rutas
    $dataAccess = $route->auth;
    $needAuth = !is_null($dataAccess) ? $dataAccess['needAuth'] : false;
    $authorizedUsers = !is_null($dataAccess) ? $dataAccess['typeUsers'] : ['ALL'];

    // Si la ruta necesita autentificación y el usuario no esta logeado
    // asignamos el controlador y método predefinido para atender tal indicencia
    $oAuthController = new App\Controllers\AuthController();
    if($needAuth && !$oAuthController->verifyAccess()){
        $controllerName = '\\App\\Controllers\\IncidenciasController';
        $actionName = 'noAutorizado';
        
    }else{
    
        // Refrescar datos de tipo de usuario en la sesión
        $oAuthController->refreshTipoUsuario();
        
        // Verificar rutas protegidas por tipo de usuario
        $access = false;
        if($authorizedUsers[0] == 'ALL'){
            $access = true;
        }else{
            foreach ($authorizedUsers as $userType){
                if($userType == Session::getUserValue('rol_nombre')){
                    $access = true;
                    break;
                }
            }
        }
        
        // Si el usuario no tiene permisos suficientes para ejecutar la ación solicitada.
        // Asignamos el controlador y método predefinido para para atender tal indicencia
        if(!$access){
            $controllerName = '\\App\\Controllers\\IncidenciasController';
            $actionName = 'permisosInsuficientes';
        }else{
            
            // Agregamos los parametros de la solicitud GET, si los hubiera
            $parametrosGet = $route->attributes;
            if(!empty($parametrosGet)){
                foreach ($route->attributes as $key => $val) {
                    $request = $request->withAttribute($key, $val);
                }
            }
        }
    
    }
}


// Ejecutamos la acción del controlador solicitado
$controller = new $controllerName;
$response = $controller->$actionName($request);

// Agregamos los headers de la pagina
foreach($response->getHeaders() as $name => $values){
    foreach($values as $value){
        header(sprintf('%s: %s', $name, $value), false);
    }
}
// Agregamos el codigo de respuesta de la pagina
http_response_code($response->getStatusCode());

// Imprimimos la pagina
echo $response->getBody();