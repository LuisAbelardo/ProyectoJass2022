<?php 

/**
 * En esa función se registra todas las rutas de la aplicación
 * @param \Aura\Router\Map
 * @return \Aura\Router\Map 
 */
function routes($map){

    // RUTA RAIZ DEL SISTEMA ----------------------------------------------------------------------------------------------------------------
    
    // Formulario de autentificación
    $map->get('inicio', '/', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getFormLogin'
    ]);
    
    
    // RUTAS DE AUTENTIFICACIÓN -------------------------------------------------------------------------------------------------------
    
    // Formulario de autentificación
    $map->get('userFormLogin', '/login', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getFormLogin'
    ]);
    
    // Validacion de datos de login
    $map->post('userLogin', '/login/verify', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getLogin'
    ]);
    
    // Terminar la sesión
    $map->get('userLogout', '/logout', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getLogout'
    ]);
    
    // Formulario olvide mi password
    $map->get('userFormForgotPassword', '/recuperarpassword', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getFormRecoveryPassword'
    ]);
    
    // Crear token para recuperar password
    $map->post('userCreateTokenRecoveryPassword', '/create/tokenrecoverypassword', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'createTokenRecoveryPassword'
    ]);
    
    // Formulario reasignar mi password
    $map->get('userFormReplacePassword', '/reemplazarpassword/{usuarioId}', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'getFormReplacePassword'
    ])->tokens(['usuarioId' => '\d+', ]);
    
    // Remplazar password
    $map->post('userReplacePassword', '/replacepassword', [
        'controller' => '\\App\\Controllers\\AuthController',
        'action' => 'replacePassword'
    ]);
    
    
    // DASHBOARD --------------------------------------------------------------------------------------------------------------------
    
    // Panel de bienvenida
    $map->get('dashboard', '/home', [
        'controller' => '\\App\\Controllers\\DashboardController',
        'action' => 'index'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    

    // RUTAS DE USUARIO -------------------------------------------------------------------------------------------------------------

    // Mostrar formulario nuevo usuario
    $map->get('userFormNew', '/usuario/nuevo', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getFormNewUser'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);

    // Crear nuevo usuario
    $map->post('userCreateNew', '/usuario/create', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'createNewUser'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Ver lista de usuarios
    $map->get('userList', '/usuario/lista', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getListUsers'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Ver lista de usuarios según filtro solicitado
    $map->get('userListFilter', '/usuario/lista/filtro', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getFilterListUsers'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Ver detalle de usuario
    $map->get('userDetail', '/usuario/detalle/{userId}', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getDetailUser'
    ])
    ->tokens(['userId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);;
    
    // Mostrar vista para editar usuario 
    $map->get('userFormEdit', '/usuario/editar/{userId}', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getFormEditUser'
    ])
    ->tokens(['userId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Editar datos de usuario
    $map->post('userEditData', '/usuario/update', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'updateUserData'
    ])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Mostrar vista para editar password de usuario
    $map->get('userFormEditPassword', '/usuario/editar/password/{userId}', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'getFormEditPasswordUser'
    ])
    ->tokens(['userId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Editar password de usuario
    $map->post('userEditPassword', '/usuario/update/password', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'updateUserPassword'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Desbloquear usuario por intentos fallidos
    $map->post('userUnlock', '/usuario/unlock/intentosfallidos', [
        'controller' => '\\App\\Controllers\\UserController',
        'action' => 'unlockUserForFailedAttempts'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Eliminar usuario
    //$map->post('userDelete', '/usuario/delete', [
    //    'controller' => '\\App\\Controllers\\UserController',
    //    'action' => 'deleteUser'
    //]);
    
    
    // RUTAS DE SERVICIO -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo servicio
    //$map->get('servicioFormNew', '/servicio/nuevo', [
    //    'controller' => '\\App\\Controllers\\ServicioController',
    //    'action' => 'getFormNewServicio'
    //]);
    
    // Crear nuevo servicio
    //$map->post('servicioCreateNew', '/servicio/create', [
    //    'controller' => '\\App\\Controllers\\ServicioController',
    //    'action' => 'createNewServicio'
    //]);
    
    // Ver lista de servicios
    $map->get('servicioList', '/servicio/lista', [
        'controller' => '\\App\\Controllers\\ServicioController',
        'action' => 'getListServicios'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de servicios según filtro solicitado
    $map->get('servicioListFilter', '/servicio/lista/filtro', [
        'controller' => '\\App\\Controllers\\ServicioController',
        'action' => 'getFilterListServicios'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de servicio
    $map->get('servicioDetail', '/servicio/detalle/{servicioId}', [
        'controller' => '\\App\\Controllers\\ServicioController',
        'action' => 'getDetailServicio'
    ])
    ->tokens(['servicioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar servicio
    $map->get('servicioFormEdit', '/servicio/editar/{servicioId}', [
        'controller' => '\\App\\Controllers\\ServicioController',
        'action' => 'getFormEditServicio'
    ])
    ->tokens(['servicioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos de servicio
    $map->post('servicioEditData', '/servicio/update', [
        'controller' => '\\App\\Controllers\\ServicioController',
        'action' => 'updateServicioData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar servicio
    //$map->post('servicioDelete', '/servicio/delete', [
    //    'controller' => '\\App\\Controllers\\ServicioController',
    //    'action' => 'deleteServicio'
    //]);
    
    
    // RUTAS DE CALLE -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nueva calle
    $map->get('calleFormNew', '/calle/nuevo', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'getFormNewCalle'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nueva calle
    $map->post('calleCreateNew', '/calle/create', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'createNewCalle'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de calles
    $map->get('calleList', '/calle/lista', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'getListCalles'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de calles según filtro solicitado
    $map->get('calleListFilter', '/calle/lista/filtro', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'getFilterListCalles'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de calle
    $map->get('calleDetail', '/calle/detalle/{calleId}', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'getDetailCalle'
    ])
    ->tokens(['calleId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar calle
    $map->get('calleFormEdit', '/calle/editar/{calleId}', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'getFormEditCalle'
    ])
    ->tokens(['calleId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos de calle
    $map->post('calleEditData', '/calle/update', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'updateCalleData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar calle
    $map->post('calleDelete', '/calle/delete', [
        'controller' => '\\App\\Controllers\\CalleController',
        'action' => 'deleteCalle'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE SECTOR -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo sector
    $map->get('sectorFormNew', '/sector/nuevo', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'getFormNewSector'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo sector
    $map->post('sectorCreateNew', '/sector/create', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'createNewSector'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de sectores
    $map->get('sectorList', '/sector/lista', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'getListSectores'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de sectores según filtro solicitado
    $map->get('sectorListFilter', '/sector/lista/filtro', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'getFilterListSectores'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de sector
    $map->get('sectorDetail', '/sector/detalle/{sectorId}', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'getDetailSector'
    ])
    ->tokens(['sectorId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar sector
    $map->get('sectorFormEdit', '/sector/editar/{sectorId}', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'getFormEditSector'
    ])
    ->tokens(['sectorId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos de sector
    $map->post('sectorEditData', '/sector/update', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'updateSectorData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar sector
    $map->post('sectorDelete', '/sector/delete', [
        'controller' => '\\App\\Controllers\\SectorController',
        'action' => 'deleteSector'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE TIPO PREDIO -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo tipo predio
    $map->get('tipoPredioFormNew', '/tipopredio/nuevo', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'getFormNewTipoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo tipo predio
    $map->post('tipoPredioCreateNew', '/tipopredio/create', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'createNewTipoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de tipos de predios
    $map->get('tipoPredioList', '/tipopredio/lista', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'getListTiposPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de tipos de predio según filtro solicitado
    $map->get('tipoPredioListFilter', '/tipopredio/lista/filtro', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'getFilterListTiposPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de tipo de predio
    $map->get('tipoPredioDetail', '/tipopredio/detalle/{tipoPredioId}', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'getDetailTipoPredio'
    ])
    ->tokens(['tipoPredioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar tipo predio
    $map->get('tipoPredioFormEdit', '/tipopredio/editar/{tipoPredioId}', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'getFormEditTipoPredio'
    ])
    ->tokens(['tipoPredioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos de tipo predio
    $map->post('tipoPredioEditData', '/tipopredio/update', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'updateTipoPredioData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar tipo predio
    $map->post('tipoPredioDelete', '/tipopredio/delete', [
        'controller' => '\\App\\Controllers\\TipoPredioController',
        'action' => 'deleteTipoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE TIPO DE USO DE PREDIO -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo tipo de uso de predio
    $map->get('tipoUsoPredioFormNew', '/tipousopredio/nuevo', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'getFormNewTipoUsoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo tipo de uso de predio
    $map->post('tipoUsoPredioCreateNew', '/tipousopredio/create', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'createNewTipoUsoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de tipos de uso de predio
    $map->get('tipoUsoPredioList', '/tipousopredio/lista', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'getListTiposUsoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de tipos de uso de predio según filtro solicitado
    $map->get('tipoUsoPredioListFilter', '/tipousopredio/lista/filtro', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'getFilterListTiposUsoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de tipo de uso de predio
    $map->get('tipoUsoPredioDetail', '/tipousopredio/detalle/{tipoUsoPredioId}', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'getDetailTipoUsoPredio'
    ])
    ->tokens(['tipoUsoPredioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar tipo de uso de predio
    $map->get('tipoUsoPredioFormEdit', '/tipousopredio/editar/{tipoUsoPredioId}', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'getFormEditTipoUsoPredio'
    ])
    ->tokens(['tipoUsoPredioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos de tipo de uso de predio
    $map->post('tipoUsoPredioEditData', '/tipousopredio/update', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'updateTipoUsoPredioData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar tipo de uso de predio
    $map->post('tipoUsoPredioDelete', '/tipousopredio/delete', [
        'controller' => '\\App\\Controllers\\TipoUsoPredioController',
        'action' => 'deleteTipoUsoPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE PREDIOS -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo predio
    $map->get('predioFormNew', '/predio/nuevo', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getFormNewPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo predio
    $map->post('predioCreateNew', '/predio/create', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'createNewPredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de predios
    $map->get('predioList', '/predio/lista', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getListPredios'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de predios según filtro solicitado
    $map->get('predioListFilter', '/predio/lista/filtro', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getFilterListPredios'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de predio
    $map->get('predioDetail', '/predio/detalle/{predioId}', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getDetailPredio'
    ])
    ->tokens(['predioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de predio (respuesta en formato JSON)
    $map->get('predioDetailJson', '/predio/detalle/json/{predioId}', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getDetailPredioJson'
    ])
    ->tokens(['predioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar predio
    $map->get('predioFormEdit', '/predio/editar/{predioId}', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'getFormEditPredio'
    ])
    ->tokens(['predioId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos predio
    $map->post('predioEditData', '/predio/update', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'updatePredioData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar predio
    $map->post('predioDelete', '/predio/delete', [
        'controller' => '\\App\\Controllers\\PredioController',
        'action' => 'deletePredio'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE CLIENTE -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo cliente natural
    $map->get('clienteFormNewNatural', '/cliente/nuevo/natural', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getFormNewClienteNatural'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar formulario nuevo cliente juridico
    $map->get('clienteFormNewJuridico', '/cliente/nuevo/juridico', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getFormNewClienteJuridico'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo cliente
    $map->post('clienteCreateNew', '/cliente/create', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'createNewCliente'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de clientes
    $map->get('clienteList', '/cliente/lista', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getListClientes'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de clientes según filtro solicitado
    $map->get('clienteListFilter', '/cliente/lista/filtro', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getFilterListClientes'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de cliente
    $map->get('clienteDetail', '/cliente/detalle/{clienteId}', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getDetailCliente'
    ])
    ->tokens(['clienteId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de cliente (respuesta en formato JSON)
    $map->get('clienteDetailJson', '/cliente/detalle/json/{clienteDoc}', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getDetailClienteFromDocJson'
    ])
    ->tokens(['clienteDoc' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar cliente
    $map->get('clienteFormEdit', '/cliente/editar/{clienteId}', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'getFormEditCliente'
    ])
    ->tokens(['clienteId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos cliente
    $map->post('clienteEditData', '/cliente/update', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'updateClienteData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar cliente
    $map->post('clienteDelete', '/cliente/delete', [
        'controller' => '\\App\\Controllers\\ClienteController',
        'action' => 'deleteCliente'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE SERVICIO ADICIONAL RECIBO -----------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo servicio adicional recibo
    $map->get('montoAdicionalRcbFormNew', '/montoadicionalrecibo/nuevo', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'getFormNewServAdicionalRcb'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo servicio adicional recibo
    $map->post('montoAdicionalRcbCreateNew', '/montoadicionalrecibo/create', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'createNewServAdicionalRcb'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de servicios adicional recibo
    $map->get('montoAdicionalRcbList', '/montoadicionalrecibo/lista', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'getListServAdicionalRcb'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de servicios adicional recibo según filtro solicitado
    $map->get('montoAdicionalRcbListFilter', '/montoadicionalrecibo/lista/filtro', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'getFilterListServAdicionalRcb'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de servicio adicional recibo
    $map->get('montoAdicionalRcbDetail', '/montoadicionalrecibo/detalle/{servAdicionalRcbId}', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'getDetailServAdicionalRcb'
    ])
    ->tokens(['servAdicionalRcbId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar servicio adicional recibo
    $map->get('montoAdicionalRcbFormEdit', '/montoadicionalrecibo/editar/{servAdicionalRcbId}', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'getFormEditServAdicionalRcb'
    ])
    ->tokens(['servAdicionalRcbId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos servicio adicional recibo
    $map->post('montoAdicionalRcbEditData', '/montoadicionalrecibo/update', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'updateServAdicionalRcbData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Eliminar servicio adicional recibo
    $map->post('montoAdicionalRcbbDelete', '/montoadicionalrecibo/delete', [
        'controller' => '\\App\\Controllers\\ServicioAdicionalRcbController',
        'action' => 'deleteServAdicionalRcb'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE CONTRATO ---------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo contrato
    $map->get('contratoFormNew', '/contrato/nuevo', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getFormNewContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo contrato
    $map->post('contratoCreateNew', '/contrato/create', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'createNewContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de contratos
    $map->get('contratoList', '/contrato/lista', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getListContratos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de contratos según filtro solicitado
    $map->get('contratoListFilter', '/contrato/lista/filtro', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getFilterListContratos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de contrato
    $map->get('contratoDetail', '/contrato/detalle/{contratoId}', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getDetailContrato'
    ])
    ->tokens(['contratoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver detalle de contrato (respuesta en formato JSON)
    $map->get('contratoDetailJson', '/contrato/detalle/json/{contratoId}', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getDetailContratoJson'
    ])
    ->tokens(['contratoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar vista para editar contrato
    $map->get('contratoFormEdit', '/contrato/editar/{contratoId}', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'getFormEditContrato'
    ])
    ->tokens(['contratoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Editar datos contrato
    $map->post('contratoEditData', '/contrato/update', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'updateContratoData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Anular contrato
    $map->post('contratoAnnular', '/contrato/annular', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'annularContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);


    // Suspender contrato
    $map->post('contratoSuspend', '/contrato/suspend', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'suspendContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);

    // Reconectar contrato
    $map->post('contratoReconnect', '/contrato/reconnect', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'reconnectContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);

    // Mantenimiento contrato
    $map->post('contratoMaintenance', '/contrato/maintenance', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'onMaintenanceContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);

    // Fin Mantenimiento contrato
    $map->post('contratoFinMaintenance', '/contrato/endMaintenance', [
        'controller' => '\\App\\Controllers\\ContratoController',
        'action' => 'endMaintenanceContrato'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE RECIBO ---------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario crear recibos
    $map->get('reciboFormNews', '/recibo/generar', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'getFormNewRecibos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevos recibos
    $map->post('reciboCreateNews', '/recibo/create', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'createNewRecibos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de recibos
    $map->get('reciboList', '/recibo/lista', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'getListRecibos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de recibos según filtro solicitado
    $map->get('reciboListFilter', '/recibo/lista/filtro', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'getFilterListRecibos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Mostrar formulario recibos por periodo
    $map->get('reciboFormImpresionMasiva', '/recibo/impresionmasiva', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'getFormImpresionMasiva'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Verificar si los recibos estan vencidos
    $map->post('reciboCheckExpired', '/recibo/verificarvencidos', [
        'controller' => '\\App\\Controllers\\ReciboController',
        'action' => 'checkExpired'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    
    
    // RUTAS DE INGRESO -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario pago recibo
    $map->get('ingresoFormNewPagoRecibo', '/ingreso/recibo/nuevo/{reciboId}', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getFormNewPagoRecibo'
    ])
    ->tokens(['reciboId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Mostrar formulario pago cuota extraordinaria
    $map->get('ingresoFormNewPagoCuotaExtraordinaria', '/ingreso/cuotaextraordinaria/nuevo/{cuotaExtraId}', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getFormNewPagoCuotaExtraordinaria'
    ])
    ->tokens(['cuotaExtraId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Mostrar formulario ingreso otros
    $map->get('ingresoFormNewIngresoOtros', '/ingreso/otros/nuevo', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getFormNewIngresoOtros'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Crear nuevo ingreso por pago de recibo
    $map->post('ingresoCreateNewPagoRecibo', '/ingreso/recibo/create', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'createNewIngresoPagoRecibo'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Crear nuevo ingreso por pago de cuota extraordinaria
    $map->post('ingresoCreateNewPagoCutaExtraordinaria', '/ingreso/cuotaextraordinaria/create', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'createNewIngresoPagoCuotaExtraordinaria'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Crear nuevo ingreso por otros motivos
    $map->post('ingresoCreateNewOtros', '/ingreso/otros/create', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'createNewIngresoOtros'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'COBRANZAS']
    ]);
    
    // Ver lista de ingresos
    $map->get('ingresoList', '/ingreso/lista', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getListIngresos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de ingresos según filtro solicitado
    $map->get('ingresoListFilter', '/ingreso/lista/filtro', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getFilterListIngresos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver detalle de ingreso
    $map->get('ingresoDetail', '/ingreso/detalle/{ingresoId}', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'getDetailIngreso'
    ])
    ->tokens(['ingresoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Anular ingreso
    $map->post('ingresoAnnular', '/ingreso/annular', [
        'controller' => '\\App\\Controllers\\IngresoController',
        'action' => 'annularIngreso'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    
    // RUTAS DE EGRESO -------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo egreso
    $map->get('egresoFormNew', '/egreso/nuevo', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'getFormNewEgreso'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'AUXILIAR']
    ]);
    
    // Crear nuevo egreso
    $map->post('egresoCreateNew', '/egreso/create', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'createNewEgreso'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO', 'AUXILIAR']
    ]);
    
    // Ver lista de egresos
    $map->get('egresoList', '/egreso/lista', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'getListEgresos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de egresos según filtro solicitado
    $map->get('egresoListFilter', '/egreso/lista/filtro', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'getFilterListEgresos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver detalle de egreso
    $map->get('egresoDetail', '/egreso/detalle/{egresoId}', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'getDetailEgreso'
    ])
    ->tokens(['egresoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Anular egreso
    $map->post('egresoAnnular', '/egreso/annular', [
        'controller' => '\\App\\Controllers\\EgresoController',
        'action' => 'annularEgreso'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    
    // RUTAS DE TRANSFERENCIA ---------------------------------------------------------------------------------------------------------------
    
    // Mostrar formulario nueva transferencia
    $map->get('transferenciaFormNew', '/transferencia/nuevo', [
        'controller' => '\\App\\Controllers\\TransferenciaController',
        'action' => 'getFormNewTransferencia'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nueva transferencia
    $map->post('transferenciaCreateNew', '/transferencia/create', [
        'controller' => '\\App\\Controllers\\TransferenciaController',
        'action' => 'createNewTransferencia'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    
    // RUTAS DE FINANCIAMIENTO DE RECIBO ----------------------------------------------------------------------------------------
    
    // Verifica recibos para nuevo financiamiento
    $map->post('finanCheckRboVencidos', '/financiamiento/checkrbovencidos', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'checkRboForFinanciamiento'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar formulario nuevo financiamiento
    $map->get('finanFormNew', '/financiamiento/nuevo', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'getFormNewFinanciamiento'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo financiamiento
    $map->post('finanCreateNew', '/financiamiento/create', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'createNewFinanciamiento'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Confirmar financiamiento
    $map->post('finanConfirm', '/financiamiento/confirmar', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'confirmFinanciamiento'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de financiamientos
    $map->get('finanList', '/financiamiento/lista', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'getListFinanciamientos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de financiamientos según filtro solicitado
    $map->get('finanListFilter', '/financiamiento/lista/filtro', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'getFilterListFinanciamientos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver detalle de financiamiento
    $map->get('finanDetail', '/financiamiento/detalle/{financiamientoId}', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'getDetailFinanciamiento'
    ])
    ->tokens(['financiamientoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Annular financiamiento
    $map->post('finanAnnular', '/financiamiento/annular', [
        'controller' => '\\App\\Controllers\\FinanciamientoController',
        'action' => 'annularFinanciamiento'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    
    // RUTAS DE PROYECTOS ----------------------------------------------------------------------------------------
    
    // Mostrar formulario nuevo proyecto
    $map->get('proyectoFormNew', '/proyecto/nuevo', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'getFormNewProyecto'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Crear nuevo proyecto
    $map->post('proyectoCreateNew', '/proyecto/create', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'createNewProyecto'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Confirmar proyecto
    $map->post('proyectoConfirm', '/proyecto/confirmar', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'confirmProyecto'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Ver lista de proyectos
    $map->get('proyectoList', '/proyecto/lista', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'getListProyectos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de proyectos según filtro solicitado
    $map->get('proyectoListFilter', '/proyecto/lista/filtro', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'getFilterListProyectos'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver detalle de proyecto
    $map->get('proyectoDetail', '/proyecto/detalle/{proyectoId}', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'getDetailProyecto'
    ])
    ->tokens(['proyectoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Annular proyecto
    $map->post('proyectoAnnular', '/proyecto/annular', [
        'controller' => '\\App\\Controllers\\ProyectoController',
        'action' => 'annularProyecto'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    
    // RUTAS DE CUOTA EXTRAORDINARIA ----------------------------------------------------------------------------------------
    
    // Ver lista de cuotas extraordinarias
    $map->get('cuotaExtraList', '/cuotaextraordinaria/lista', [
        'controller' => '\\App\\Controllers\\CuotaExtraordinariaController',
        'action' => 'getListCuotasExtraordinarias'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Ver lista de cuotas extraordinarias según filtro solicitado
    $map->get('cuotaExtraListFilter', '/cuotaextraordinaria/lista/filtro', [
        'controller' => '\\App\\Controllers\\CuotaExtraordinariaController',
        'action' => 'getFilterListCuotasExtraordinarias'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    
    // RUTAS DE IGV ---------------------------------------------------------------------------------------------------------------
    
    // Mostrar vista para editar igv
    $map->get('igvFormEdit', '/igv/editar', [
        'controller' => '\\App\\Controllers\\IgvController',
        'action' => 'getFormEditIgv'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    // Editar datos igv
    $map->post('igvEditData', '/igv/update', [
        'controller' => '\\App\\Controllers\\IgvController',
        'action' => 'updateIgvData'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR']
    ]);
    
    
    // RUTAS DE REPORTE ---------------------------------------------------------------------------------------------------------------
    
    // Mostrar recibo
    $map->get('reporteRecibo', '/reporte/recibo/{reciboId}', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getRecibo'
    ])
    ->tokens(['reciboId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Mostrar ticket de ingreso
    $map->get('reporteIngresoTicket', '/reporte/ingreso/{ingresoId}', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getTicketIngreso'
    ])
    ->tokens(['ingresoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Mostrar ticket de egreso
    $map->get('reporteEgresoTicket', '/reporte/egreso/{egresoId}', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getTicketEgreso'
    ])
    ->tokens(['egresoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Mostrar recibos por periodo y sector
    $map->get('reporteReciboImpresionMasiva', '/reporte/recibos/{sectorId}/{month}/{year}', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getRecibos'
    ])
    ->tokens(['sectorId' => '\d+', 'year' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar contrato de financiamiento
    $map->get('reporteContratoFinanciamiento', '/reporte/contratofinanciamiento/{financiamientoId}', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getContratoFinanciamiento'
    ])
    ->tokens(['financiamientoId' => '\d+'])
    ->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'AUXILIAR']
    ]);
    
    // Mostrar formulario para generar reporte diario
    $map->get('reporteFormArqueoDiario', '/reporte/arqueodiario', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getFormArqueoDiario'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Generar reporte arqueo diario
    $map->post('reporteArqueoDiario', '/reporte/generararqueodiario', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getArqueoDiario'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ALL']
    ]);
    
    // Mostrar formulario para generar reporte semanal
    $map->get('reporteFormArqueoSemanal', '/reporte/arqueosemanal', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getFormArqueoSemanal'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO']
    ]);
    
    // Generar reporte arqueo semanal
    $map->post('reporteArqueoSemanal', '/reporte/generararqueosemanal', [
        'controller' => '\\App\\Controllers\\ReporteController',
        'action' => 'getArqueoSemanal'
    ])->auth([
        'needAuth' => true,
        'typeUsers' => ['ADMINISTRADOR', 'TESORERO']
    ]);
    
    return $map;
}