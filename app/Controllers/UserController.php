<?php namespace App\Controllers;

use App\Libraries\InputFilter;
use App\Validation\UserValidation;
use App\Models\User;
use App\Models\TipoUsuario;
use PDOException;
use Laminas\Diactoros\ServerRequest;
use App\Libraries\LogMonolog;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Libraries\Mail;


class UserController extends BaseController{
    
    private $loggerfile;
    private $userViewsPath = '/administration/usuario/';
    
    public function __CONSTRUCT(){
        $this->loggerfile = LogMonolog::get('loggerfile');
    }
    

    /**
     * Método muestra formulario para registrar nuevo usuario
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getformNewUser()
    {
        // Asignar los tipos de usuarios a la data a enviar
        $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();
        
        return $this->renderHTML($this->userViewsPath.'userNew');
    }
    

    /**
     * Método guarda nuevo usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function createNewUser(ServerRequest $request)
    {
        $postData = (array) $request->getParsedBody() ?? [];
        $postData = arrayTrimSpaces($postData);

        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);

        // Validar los datos recibidos
        $oUserValidation = new UserValidation();
        $validation = $oUserValidation->verifyRulesNew($postData);

        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formNuevoUsuario'] = $postData;
        } else {
            
            // Los nombres de usuario no deben repetirse (son llave unica)
            if (is_null(User::where('USU_USUARIO', '=', $postData['usuario'])->first())) {
                
                try{
                    $oUser = new User();
                    $oUser->USU_NOMBRES = $postData['nombres'];
                    $oUser->USU_APELLIDOS = $postData['apellidos'];
                    $oUser->USU_USUARIO = $postData['usuario'];
                    $oUser->USU_EMAIL = $postData['email'];
                    $oUser->USU_PASSWORD = password_hash($postData['password'], PASSWORD_DEFAULT);
                    $oUser->USU_ESTADO = 1;
                    $oUser->TPU_CODIGO = $postData['tipo'];
                    $oUser->save();
                    
                    // Redireccionamos a la vista detalle de usuario
                    return new RedirectResponse('/usuario/detalle/'.$oUser->USU_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Usuario no resgistrado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó el usuario'];
                }
                
            }else{
                
                // Controlando errores de validación por repetición de nombre de usuario
                $this->respuestaCodigo = 400;
                $this->respuestaEstado = 'error de validación';
                $this->respuestaEstadoDetalle = ['Nombre de usuario no disponible'];
                $this->respuestaData['formNuevoUsuario'] = $postData;
            }

            
        }
        
        // Asignar los tipos de usuarios a la data a enviar
        $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();

        return $this->renderHTML($this->userViewsPath.'userNew');
    }
    
    
    /**
     * Método muestra lista de usuarios
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getListUsers(ServerRequest $request)
    {
        // Asignar datos de paginacion
        $paginaActual = 1;
        $cantidadRegistros = (int) User::count();
        $pagination = User::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['users'] = User::offset($pagination['paginaOffset'])
                                                    ->limit($pagination['paginaLimit'])
                                                    ->orderBy('USU_CODIGO', 'desc')
                                                    ->get()->toArray();
        }else{
            $this->respuestaData['users'] = [];
        }
        
        // Asignar los tipos de usuarios a la data a enviar
        $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();
        
        return $this->renderHTML($this->userViewsPath.'userList');
    }
    
    
    /**
     * Método muestra lista de usuarios según filtro solicitado
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFilterListUsers(ServerRequest $request)
    {
        $queryData = (array) $request->getQueryParams() ?? [];
        $queryData = arrayTrimSpaces($queryData);
        
        // Limpiar etiquetas html de los parametros recibidos
        $oInputFilter = new InputFilter();
        $queryData = $oInputFilter->process($queryData);
        
        // Condiciones para mostrar usuarios
        $oUser = new User();
        if(isset($queryData['filterCodigo']) && $queryData['filterCodigo'] != ''){
            $oUser = $oUser->where('USU_CODIGO', '=', $queryData['filterCodigo']);
        }
        if(isset($queryData['filterUsuario']) && $queryData['filterUsuario'] != '') {
            $oUser = $oUser->where('USU_USUARIO', '=', $queryData['filterUsuario']);
        }
        if(isset($queryData['filterNombres']) && $queryData['filterNombres'] != '') {
            $oUser = $oUser->where('USU_NOMBRES', 'LIKE', $queryData['filterNombres'].'%');
        }
        if(isset($queryData['filterApellidos']) && $queryData['filterApellidos'] != '') {
            $oUser = $oUser->where('USU_APELLIDOS', 'LIKE', $queryData['filterApellidos'].'%');
        }
        if(isset($queryData['filterEmail']) && $queryData['filterEmail'] != '') {
            $oUser = $oUser->where('USU_EMAIL', '=', $queryData['filterEmail']);
        }
        if(isset($queryData['filterTipo']) && $queryData['filterTipo'] != '' 
                && is_numeric($queryData['filterTipo']) && $queryData['filterTipo'] != -1) {
            $oUser = $oUser->where('TPU_CODIGO', '=', $queryData['filterTipo']);
        }
        if(isset($queryData['filterEstado']) && $queryData['filterEstado'] != ''
                && is_numeric($queryData['filterEstado']) && $queryData['filterEstado'] != -1) {
            $oUser = $oUser->where('USU_ESTADO', '=', $queryData['filterEstado']);
        }
        
        // Asignar datos de paginación
        $paginaActual = (int) (isset($queryData['filterPaginaActual']) 
                                && $queryData['filterPaginaActual'] != '' 
                                && is_numeric($queryData['filterPaginaActual']))
                                ? $queryData['filterPaginaActual'] : 1;
                                $cantidadRegistros = $oUser->count();
        $pagination = User::paginate($cantidadRegistros, $paginaActual);
        
        // Asignar datos de paginación a la data a enviar
        $this->respuestaData['pagination'] = $pagination;
        
        // Asignar parametros de filtros obtenidos a la data a enviar
        $this->respuestaData['formFilterListUsers'] = $queryData;
        
        // Si hay registros estos serán enviados de lo contrario se enviará un array vacio
        if($cantidadRegistros > 0){
            $this->respuestaData['users'] = $oUser->offset($pagination['paginaOffset'])
                                                                ->limit($pagination['paginaLimit'])
                                                                ->orderBy('USU_CODIGO', 'desc')
                                                                ->get()->toArray();
        }else{
            $this->respuestaData['users'] = [];
        }
        
        // Asignar los tipos de usuarios a la data a enviar
        $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();
        
        return $this->renderHTML($this->userViewsPath.'userList');
    }
    
    
    /**
     * Método muestra todos los datos de usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getDetailUser(ServerRequest $request)
    {
        $codeUser = (int) $request->getAttribute('userId');
        
        $user = User::find($codeUser);
        
        if($user != null){
            $this->respuestaData['usuario'] = $user->toArray();
            $this->respuestaData['usuarioTipo'] = $user->tipoUsuario->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el usuario solicitado'];
            $this->respuestaData['usuarioId'] = $codeUser;
        }
        
        return $this->renderHTML($this->userViewsPath.'userDetail');
    }
    
    
    /**
     * Método muestra la vista para editar datos de usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditUser(ServerRequest $request) {
        
        $codeUser = (int) $request->getAttribute('userId');
        
        $user = User::find($codeUser);
        
        if($user != null){
            $this->respuestaData['usuario'] = $user->toArray();
            $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el usuario solicitado'];
            $this->respuestaData['usuarioId'] = $codeUser;
        }
        
        return $this->renderHTML($this->userViewsPath.'userEdit');
    }
    
    
    /**
     * Método para actualizar datos de usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    public function updateUserData(ServerRequest $request) {
        $postData = $request->getParsedBody(); 
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oUserValidation = new UserValidation();
        $validation = $oUserValidation->veryRulesUpdate($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarUsuario'] = $postData;
        } else {
            
            // Usuario a editar
            $oUser = User::find($postData['codigo']);
            
            if(!is_null($oUser)){
                
                // Los nombres de usuario no deben repetirse (son llave unica)
                if (User::where('USU_USUARIO', '=', $postData['usuario'])->where('USU_CODIGO', '<>', $postData['codigo'])->count() == 0) {
                    
                    try{
                        $oUser->USU_NOMBRES = $postData['nombres'];
                        $oUser->USU_APELLIDOS = $postData['apellidos'];
                        $oUser->USU_USUARIO = $postData['usuario'];
                        $oUser->USU_EMAIL = $postData['email'];
                        $oUser->USU_ESTADO = (int) $postData['estado'];
                        $oUser->TPU_CODIGO = $postData['tipo'];
                        $oUser->save();
                        
                        // Redireccionamos a la vista detalle de usuario
                        return new RedirectResponse('/usuario/detalle/'.$oUser->USU_CODIGO);
                        
                    }catch(PDOException $e){
                        $this->loggerfile->debug('Usuario no actualizado. Mensaje:'.$e->getMessage());
                        $this->respuestaCodigo = 500;
                        $this->respuestaEstado = 'error de servidor';
                        $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se guardó los cambios de usuario'];
                    }
                    
                }else{
                    
                    // Controlando errores de validación por repetición de nombre de usuario
                    $this->respuestaCodigo = 400;
                    $this->respuestaEstado = 'error de validación';
                    $this->respuestaEstadoDetalle = ['Nombre de usuario no disponible'];
                    $this->respuestaData['formEditarUsuario'] = $postData;
                }
            }else{
                
                // Controlando error por no encontrar usuario a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el usuario solicitado'];
                $this->respuestaData['formEditarUsuario'] = $postData;
            }
            
        }
        
        // Asignar los tipos de usuarios a la data a enviar
        $this->respuestaData['usuarioTipos'] = TipoUsuario::all()->toArray();
        
        return $this->renderHTML($this->userViewsPath.'userEdit');
    }
    
    
    /**
     * Método muestra la vista para editar password de usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function getFormEditPasswordUser(ServerRequest $request) {
        
        $codeUser = (int) $request->getAttribute('userId');
        
        $user = User::find($codeUser);
        
        if($user != null){
            $this->respuestaData['usuario'] = $user->toArray();
        }else{
            $this->respuestaCodigo = 404;
            $this->respuestaEstado = 'recurso no encontrado';
            $this->respuestaEstadoDetalle = ['No se econtro el usuario solicitado'];
            $this->respuestaData['usuarioId'] = $codeUser;
        }
        
        return $this->renderHTML($this->userViewsPath.'userEditPassword');
    }
    
   
    /**
     * Método edita password de usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function updateUserPassword(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oUserValidation = new UserValidation();
        $validation = $oUserValidation->veryRulesUpadatePassword($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEditarPasswordUsuario'] = $postData;
        } else {
            
            // Usuario a editar
            $oUser = User::find($postData['codigo']);
            
            if(!is_null($oUser)){
                
                try{
                    $oUser->USU_PASSWORD = password_hash($postData['password'], PASSWORD_DEFAULT);
                    $oUser->save();
                    
                    // Redireccionamos a la vista detalle de usuario
                    return new RedirectResponse('/usuario/detalle/'.$oUser->USU_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Password de usuario no actualizado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se actualizó el password de usuario'];
                }
                
            }else{
                
                // Controlando error por no encontrar usuario a editar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el usuario solicitado'];
                $this->respuestaData['formEditarUsuario'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->userViewsPath.'userEditPassword');
    }
    
    
    /**
     * Método desbloquea usuario (bloqueado por limite de intentos fallidos)
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return \Laminas\Diactoros\Response\HtmlResponse
     */
    public function unlockUserForFailedAttempts(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oUserValidation = new UserValidation();
        $validation = $oUserValidation->veryRulesUnlockForFailedAttempts($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formDesbloquearUsuario'] = $postData;
        } else {
            
            // Usuario a desbloquear
            $oUser = User::find($postData['codigo']);
            
            if(!is_null($oUser)){
                
                try{
                    $oUser->USU_INTENTOS_FALLIDOS = 0;
                    $oUser->save();
                    
                    // Redireccionamos a la vista detalle de usuario
                    return new RedirectResponse('/usuario/detalle/'.$oUser->USU_CODIGO);
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Usuario no desbloqueado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se desbloqueó el usuario'];
                }
                
            }else{
                
                // Controlando error por no encontrar usuario a desbloquear
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el usuario solicitado'];
                $this->respuestaData['formDesbloquearUsuario'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->userViewsPath.'userDetail');
    }
    
    
    /**
     * Método elimina usuario
     * @param \Laminas\Diactoros\ServerRequest $request
     * @return mixed
     */
    /*public function deleteUser(ServerRequest $request) {
        
        $postData = $request->getParsedBody();
        $postData = arrayTrimSpaces($postData);
        
        // Limpiar etiquetas html de los parametros recividos
        $oInputFilter = new InputFilter();
        $postData = $oInputFilter->process($postData);
        
        // Validar los datos recividos
        $oUserValidation = new UserValidation();
        $validation = $oUserValidation->veryRulesDelete($postData);
        
        // Verificar si la validación encontró errores
        if ($validation->fails()) {
            
            // Controlando errores de validación por entrada de datos
            $this->respuestaCodigo = 400;
            $this->respuestaEstado = 'error de validacion';
            $this->respuestaEstadoDetalle = $validation->errors()->firstOfAll();
            $this->respuestaData['formEliminarUsuario'] = $postData;
        } else {
            
            // Usuario a eliminar
            $oUser = User::find($postData['codigo']);
            
            if(!is_null($oUser)){
                
                try{
                    $oUser->delete();
                    
                    // Redireccionamos a la vista lista de usuarios
                    return new RedirectResponse('/usuario/lista');
                    
                }catch(PDOException $e){
                    $this->loggerfile->debug('Usuario no eliminado. Mensaje:'.$e->getMessage());
                    $this->respuestaCodigo = 500;
                    $this->respuestaEstado = 'error de servidor';
                    $this->respuestaEstadoDetalle = ['Error: Algo salio mal. No se eliminó el usuario'];
                }
                
            }else{
                
                // Controlando error por no encontrar usuario a eliminar
                $this->respuestaCodigo = 404;
                $this->respuestaEstado = 'recurso no encontrado';
                $this->respuestaEstadoDetalle = ['No se encontro el usuario solicitado'];
                $this->respuestaData['formEliminarUsuario'] = $postData;
            }
            
        }
        
        return $this->renderHTML($this->userViewsPath.'userDetail');
    }*/
}
  