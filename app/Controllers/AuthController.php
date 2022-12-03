<?php namespace App\Controllers;

use App\Models\User;
use App\Libraries\Session;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Traits\HasDefaultImage;
use Laminas\Diactoros\ServerRequest;
use App\Validation\AuthValidation;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Libraries\Mail;
use App\Libraries\InputFilter;
use Carbon\Carbon;

class AuthController extends BaseController{
    
    use HasDefaultImage;

    private $viewsPath = 'administration/access/';
    
    
    /*
     * Metodo para obtener el formulario de login
     * Si existe una session activa se redirecciona al dashboard.
     * De lo contrario se carga el formulario de login
     * @return mixed
     */
    public function getFormLogin(){
        // Si el usuario tiene una sesion activa redireccionar al dashboard
        if($this->verifyAccess()){
            return new RedirectResponse('/home');
        }
        
        return $this->renderHTML($this->viewsPath."login");
    }
    

    /**
     * Método para responder a la solicitud de login.
     * Si los datos de login son correctos se crea la sesion de usurio
     * y se redirecciona al dashboard.
     * De lo contrario de carga el formulario de login
     * @param \Laminas\Diactoros\ServerRequest
     * @return mixed
     */
    public function getLogin(ServerRequest $request){

        // Recibir parametros de solicitud
        $postData = (array) $request->getParsedBody() ?? [];

        // Validar los datos recibidos
        $oLoginValidation = new AuthValidation();
        $validation = $oLoginValidation->veryRulesLogin($postData);

        // Verificar si la validación encontro errores
        if ($validation->fails()) {
            
            // Controlando errores de validación
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            
        } else {
            
            $user = User::where(Capsule::raw('BINARY `USU_USUARIO`'), '=', $postData['user'])->first();
            
            // Si el usuario existe se verifica los datos
            if(!is_null($user)){
                
                // Verificamos si el usuario ha superado el limite de intentos fallidos
                if ($user->USU_INTENTOS_FALLIDOS < 3) {
                    
                    // Verificamos si el password es correcto
                    if(password_verify($postData['password'], $user->USU_PASSWORD)){
                        
                        // Reiniciamos el contador de intentos fallidos
                       if ($user->USU_INTENTOS_FALLIDOS != 0) {
                           $user->USU_INTENTOS_FALLIDOS = 0;
                           $user->save();
                       }
                        
                        
                        // Si el estado del usuario esta activo cargamos los datos en la sessión
                        if ($user->USU_ESTADO == 1) {
                            
                            $usuario = [];
                            $usuario['id'] = $user->USU_CODIGO;
                            $usuario['nombre'] = $user->USU_NOMBRES;
                            $usuario['apellidos'] = $user->USU_APELLIDOS;
                            $tipoUsuario = $user->tipoUsuario;
                            $usuario['rol_id'] = $tipoUsuario->TPU_CODIGO;
                            $usuario['rol_nombre'] = $tipoUsuario->TPU_NOMBRE;
                            $usuario['imageUrl'] = $this->getImage('', $user->USU_NOMBRES. ' '. $user->USU_APELLIDOS);
                            
                            Session::start();
                            Session::add('jass_user', $usuario);
                            
                            return new RedirectResponse('/home');
                            
                        }else{
                            $this->respuestaCodigo = 403;
                            $this->respuestaEstado = 'permisos insuficientes';
                            $this->respuestaEstadoDetalle = ['No tiene permisos suficientes para acceder al sistema'];
                        }
                        
                    }else{
                        
                        // Aumentamos los intentos fallidos
                        $user->USU_INTENTOS_FALLIDOS = $user->USU_INTENTOS_FALLIDOS + 1;
                        $user->save();
                        
                        // Verificamos si se llego al limite de intentos fallidos
                        if ($user->USU_INTENTOS_FALLIDOS >= 3) {
                            
                            $this->respuestaCodigo = 400;
                            $this->respuestaEstado = 'error de validacion';
                            $this->respuestaEstadoDetalle = ['Usuario bloqueado por superar el limite de intentos permitidos'];
                        }else{
                            
                            $this->respuestaCodigo = 400;
                            $this->respuestaEstado = 'error en los datos de acceso';
                            $this->respuestaEstadoDetalle = ['Usuario o contraseña invalidos'];
                        }
                    }
                }else{
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['Usuario bloqueado por superar el limite de intentos permitidos'];
                }
                
            }else{
                
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error en los datos de acceso';
                $this->respuestaEstadoDetalle = ['Usuario o contraseña invalidos'];
            }
        }
        
        return $this->renderHTML($this->viewsPath."login");
    }
    
    
    /*
     * Metodo para verificar que el usuario este logeado y con estado activo
     * @return Laminas\Diactoros\Response\RedirectResponse
     */
    public function verifyAccess(){
        
        $acceso = false;
        
        $sessionUser = Session::get('jass_user');
        $sessionUserId = $sessionUser['id'] ?? null;
        if($sessionUser != null && $sessionUserId != null){
            $user = User::find($sessionUserId);
            
            if ($user != null && $user->USU_ESTADO == 1) {
                $acceso = true;
            }
        }
        
        return $acceso;
    }
    
    
    /*
     * Metodo para volver a cargar a la sesión los datos de tipo de usuario
     * @return boolean
     */
    public function refreshTipoUsuario(){
        
        $refresh = false;
        
        $sessionUser = Session::get('jass_user');
        $sessionUserId = $sessionUser['id'] ?? null;
        if($sessionUser != null && $sessionUserId != null){
            $user = User::find(Session::getUserValue('id'));
            $tipoUsuario = $user->tipoUsuario;
            Session::addUserValue('rol_id', $tipoUsuario->TPU_CODIGO);
            Session::addUserValue('rol_nombre', $tipoUsuario->TPU_NOMBRE);
            $refresh = true;
        }  
        
        return $refresh;
    }
    
    
    /*
     * Metodo para eliminar la session
     * @return Laminas\Diactoros\Response\RedirectResponse
     */
    public function getLogout(){
        Session::destroy();
        return new RedirectResponse('/login');
    }
    
    
    
    /**
     * Método muestra formulario para recuperar password
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormRecoveryPassword()
    {
        return $this->renderHTML($this->viewsPath.'recoveryPassword');
    }
    
    
    /**
     * Método para crear token para reemplazar password
     * @param \Laminas\Diactoros\ServerRequest
     * @return mixed
     */
    public function createTokenRecoveryPassword(ServerRequest $request){
        
        // Recibir parametros de solicitud
        $postData = (array) $request->getParsedBody() ?? [];
        
        // Validar los datos recibidos
        $oLoginValidation = new AuthValidation();
        $validation = $oLoginValidation->veryRulesCreateToken($postData);
        
        // Verificar si la validación encontro errores
        if ($validation->fails()) {
            
            // Controlando errores de validación
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            
        } else {
            
            $user = User::where(Capsule::raw('BINARY `USU_USUARIO`'), '=', $postData['usuario'])->first();
            
            // Si el usuario existe se verifica los datos
            if(!is_null($user)){
                    
                // Verificamos si el email es correcto
                if(!is_null($user->USU_EMAIL) && !empty($user->USU_EMAIL) && $user->USU_EMAIL == $postData['email']){
                    
                    // Si el estado del usuario es activo creamos el token
                    if ($user->USU_ESTADO == 1) {
                        
                        try {
                            
                            $token = password_hash(getToken(20),PASSWORD_DEFAULT);
                            $subject = "RECUPERACION DE CONTRASEÑA - SISTEMA J.A.S.S";
                            $body = "Se ha solicitado el reemplazo de su contraseña, si no fue usted omita este mensaje.<br/><br/>";
                            $body = $body." Pulse en el siguiente link para recuperar su contraseña <br/>";
                            $body = $body . ' <a class="f_link " href="'.getenv('PUBLIC_PATH').'/reemplazarpassword/'.$user->USU_CODIGO.'?tk='.$token.'">';
                            $body = $body . 'Recuperar contraseña</a>';
                            
                            Capsule::transaction(function() use($user, $token, $subject, $body){
                                
                                $user->USU_REQUEST_TOKEN = 1;
                                $user->USU_TOKEN_RECOVERY = $token;
                                $user->USU_TOKEN_FECHA = date('Y-m-d H:i:s');
                                $user->save();
                                
                                $oMail = new Mail();
                                $exito = $oMail->sendEmail($user->USU_EMAIL, $subject, $body);
                                
                                if (!$exito) {
                                    throw  new \Exception("Error: Algo salio mal. No se creó el token");
                                }
                                
                            });
                            
                            $this->respuestaCodigo = 200;
                            $this->respuestaEstado = 'ok';
                            $this->respuestaEstadoDetalle = ['Se envió un mensaje a su correo para restablecer su contraseña'];
                            
                            
                        } catch (\Exception $e) {
                            
                            $this->loggerfile->debug('Token no creado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se creó el token de validación'];
                        }
                        
                    }else{
                        $this->respuestaCodigo = 403;
                        $this->respuestaEstado = 'permisos insuficientes';
                        $this->respuestaEstadoDetalle = ['No tiene permisos suficientes para acceder al sistema'];
                    }
                    
                }else{
                    
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['Datos erroneos'];
                }
                
            }else{
                
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validacion';
                $this->respuestaEstadoDetalle = ['Datos erroneos'];
            }
        }
        
        return $this->renderHTML($this->viewsPath."recoveryPassword");
    }
    
    
    /**
     * Método muestra formulario para reemplazar password
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormReplacePassword(ServerRequest $request)
    {
        $showView = false;
        
        // Recibimos los atributos de url (/{attribute})
        $codeUsuario = (int) $request->getAttribute('usuarioId');
        
        // Recibimos los parametros de url (?param=value)
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Verificamos que el token este asignado
        if(isset($queryData['tk']) && $queryData['tk'] != ''){
            
            $user = User::find($codeUsuario);
            // Verificamos que el usuario exista y que se haya solicitado el token
            if (!is_null($user) && $user->USU_REQUEST_TOKEN == 1) {
                
                if ($user->USU_TOKEN_RECOVERY === $queryData['tk']) {
                    
                    // Creamos un nuevo token para reemplazar al anterior
                    $token = getToken(20);
                    $tokenHash = password_hash($token, PASSWORD_DEFAULT);
                    
                    // Reemplazamos el token por uno nuevo
                    $user->USU_TOKEN_RECOVERY = $tokenHash;
                    $user->USU_TOKEN_FECHA = date('Y-m-d H:i:s');
                    $user->save();
                    
                    $this->respuestaData['usuario'] = $user->USU_CODIGO;
                    $this->respuestaData['token'] = $token;
                    $showView= true;
                }
            }
        }
        
        
        // Verificamos se debemos mostrar la vista de recuperacion de password
        if ($showView) {
            return $this->renderHTML($this->viewsPath.'replacePassword');
        }else{
            return new RedirectResponse('/login');
        }
        
    }
    
    
    /**
     * Método para reemplazar password
     * @param \Laminas\Diactoros\ServerRequest
     * @return mixed
     */
    public function replacePassword(ServerRequest $request){
        
        // Recibir parametros de solicitud
        $postData = (array) $request->getParsedBody() ?? [];
        
        // Validar los datos recibidos
        $oLoginValidation = new AuthValidation();
        $validation = $oLoginValidation->veryRulesReplacePassword($postData);
        
        // Verificar si la validación encontro errores
        if ($validation->fails()) {
            
            // Controlando errores de validación
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formReplacePassword'] = $postData;
        } else {
            
            $user = User::find($postData['usuario']);
            
            // Verificamos si el usuario existe y que se haya solicitado el token
            if(!is_null($user) && $user->USU_REQUEST_TOKEN == 1){
                
                $fechaToken = Carbon::createFromFormat('Y-m-d H:i:s', $user->USU_TOKEN_FECHA);
                $fechaValidacion = Carbon::now();
                $tiempoTranscurrido = $fechaValidacion->diffInMinutes($fechaToken);
                
                // Verificamos si el token es valido
                if(password_verify($postData['tk'], $user->USU_TOKEN_RECOVERY) && $tiempoTranscurrido <= 5){
                    
                    // Si el estado del usuario es activo actualizamos el password
                    if ($user->USU_ESTADO == 1) {
                        
                        try {
                            
                            $user->USU_PASSWORD = password_hash($postData['password'], PASSWORD_DEFAULT);
                            $user->USU_REQUEST_TOKEN = 0;
                            $user->USU_TOKEN_RECOVERY = NULL;
                            $user->USU_TOKEN_FECHA = NULL;
                            $user->USU_INTENTOS_FALLIDOS = 0;
                            $user->save();
                                
                            $this->respuestaCodigo = 200;
                            $this->respuestaEstado = 'ok';
                            $this->respuestaEstadoDetalle = ['Password actualizado'];
                            $this->respuestaData['redirectLogin'] = true;
                                
                        } catch (\Exception $e) {
                            
                            $this->loggerfile->debug('Password no actualizado. Mensaje:'.$e->getMessage());
                            $this->respuestaCodigo = 500;
                            $this->respuestaEstado = 'error de servidor';
                            $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se actualizó el password'];
                        }
                        
                    }else{
                        $this->respuestaCodigo = 403;
                        $this->respuestaEstado = 'permisos insuficientes';
                        $this->respuestaEstadoDetalle = ['No tiene permisos suficientes para acceder al sistema'];
                    }
                    
                }else{
                    // Si el token es invalido se anula el proceso de actualización de cambio de password
                    $user->USU_REQUEST_TOKEN = 0;
                    $user->USU_TOKEN_RECOVERY = NULL;
                    $user->USU_TOKEN_FECHA = NULL;
                    $user->save();
                    
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validacion';
                    $this->respuestaEstadoDetalle = ['Token invalido'];
                }
                
            }else{
                
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validacion';
                $this->respuestaEstadoDetalle = ['Datos erroneos'];
            }
        }
        
        return $this->renderHTML($this->viewsPath."replacePassword");
    }
    
}