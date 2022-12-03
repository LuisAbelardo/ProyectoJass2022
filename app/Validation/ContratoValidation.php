<?php namespace App\Validation;

use Rakit\Validation\Validator;

class ContratoValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'predio'                        => 'required',
            'tipoUsoPredio'                 => 'required',
            'servicios'                     => 'required|array',
            'estado'                        => 'required|numeric|in:0,1,5',
            'observacion'                   => 'present',
            'aguaFechaInstalacion'          => 'default:1501-01-01|required|date:Y-m-d',
            'aguaConexionCaracteristica'    => 'default:-1|required|in:-1,sin caja,con caja',
            'aguaConexionDiametro'          => 'default:-1|required|in:-1,1/2,3/4',
            'aguaDiametroRed'               => 'default:-1|required|in:-1,2,3,4',
            'aguaMaterialConexion'          => 'default:-1|required|in:-1,pvc,fierro',
            'aguaMaterialAbrazadera'        => 'default:-1|required|in:-1,pvc,fierro',
            'aguaUbicacionCaja'             => 'default:-1|required|in:-1,vereda,jardin,interior casa,no tiene',
            'aguaMaterialCaja'              => 'default:-1|required|in:-1,concreto,ladrillo,termoplastico',
            'aguaEstadoCaja'                => 'default:-1|required|in:-1,buena,sucia,mal estado',
            'aguaMaterialTapa'              => 'default:-1|required|in:-1,concreto,ladrillo,fierro,termoplastico,no tiene',
            'aguaEstadoTapa'                => 'default:-1|required|in:-1,buena,sellada,mal estado',
            'alcFechaConexion'              => 'default:1501-01-01|required|date:Y-m-d',
            'alcConexionCaracteristica'     => 'default:-1|required|in:-1,sin caja,con caja',
            'alcTipoConexion'               => 'default:-1|required|in:-1,convencional,condominial',
            'alcConexionDiametro'           => 'default:-1|required|in:-1,4,6',
            'alcDiametroRed'                => 'default:-1|required|in:-1,4,6,8,10',
            'alcMaterialConexion'           => 'default:-1|required|in:-1,pvc,fierro',
            'alcUbicacionCaja'              => 'default:-1|required|in:-1,vereda,jardin,interior casa,no tiene',
            'alcMaterialCaja'               => 'default:-1|required|in:-1,concreto,ladrillo,termoplastico',
            'alcEstadoCaja'                 => 'default:-1|required|in:-1,buena,sucia,mal estado',
            'alcDimencionCaja'              => 'default:-1|required|in:-1,70x40 cm,60x40 cm,otro',
            'alcMaterialTapa'               => 'default:-1|required|in:-1,concreto,fierro,madera,no tiene',
            'alcEstadoTapa'                 => 'default:-1|required|in:-1,buena,sellada,mal estado,no tiene',
            'alcMedidasTapa'                => 'default:-1|required|in:-1,62x32 cm,54x34 cm,53x53 cm,otro'
        ]);
        
        $validation->setAliases([
            'tipoUsoPredio'                 => 'tipo de uso del predio',
            'aguaFechaInstalacion'          => 'fecha de instalación (agua)',
            'aguaConexionCaracteristica'    => 'caracteristica de conexion (agua)',
            'aguaConexionDiametro'          => 'diametro de conexion (agua)',
            'aguaDiametroRed'               => 'diametro de red (agua)',
            'aguaMaterialConexion'          => 'material de conexion (agua)',
            'aguaMaterialAbrazadera'        => 'material de abrazadera (agua)',
            'aguaUbicacionCaja'             => 'ubicación de caja (agua)',
            'aguaMaterialCaja'              => 'material de caja (agua)',
            'aguaEstadoCaja'                => 'estado de caja (agua)',
            'aguaMaterialTapa'              => 'material de tapa (agua)',
            'aguaEstadoTapa'                => 'estado tapa (agua)',
            'alcFechaConexion'              => 'fecha de conexion (alcantarillado)',
            'alcConexionCaracteristica'     => 'caracteristica de conexion (alcantarillado)',
            'alcTipoConexion'               => 'tipo de conexion (alcantarillado)',
            'alcConexionDiametro'           => 'diametro de conexion (alcantarillado)',
            'alcDiametroRed'                => 'diametro de red (alcantarillado)',
            'alcMaterialConexion'           => 'material de conexion (alcantarillado)',
            'alcUbicacionCaja'              => 'ubicacion de caja (alcantarillado)',
            'alcMaterialCaja'               => 'material de caja (alcantarillado)',
            'alcEstadoCaja'                 => 'estado de caja (alcantarillado)',
            'alcDimencionCaja'              => 'dimención de caja (alcantarillado)',
            'alcMaterialTapa'               => 'material de tapa (alcantarillado)',
            'alcEstadoTapa'                 => 'estado de tapa (alcantarillado)',
            'alcMedidasTapa'                => 'medidas de tapa (alcantarillado)'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'                        => 'required|numeric',
            'estadoNuevo'                   => 'default:-1|required|numeric|in:-1,1',
            'fechaInicio'                   => 'required_if:estadoNuevo,1|date:Y-m-d',
            'observacion'                   => 'present',
            'aguaFechaInstalacion'          => 'default:1501-01-01|required|date:Y-m-d',
            'aguaConexionCaracteristica'    => 'default:-1|required|in:-1,sin caja,con caja',
            'aguaConexionDiametro'          => 'default:-1|required|in:-1,1/2,3/4',
            'aguaDiametroRed'               => 'default:-1|required|in:-1,2,3,4',
            'aguaMaterialConexion'          => 'default:-1|required|in:-1,pvc,fierro',
            'aguaMaterialAbrazadera'        => 'default:-1|required|in:-1,pvc,fierro',
            'aguaUbicacionCaja'             => 'default:-1|required|in:-1,vereda,jardin,interior casa,no tiene',
            'aguaMaterialCaja'              => 'default:-1|required|in:-1,concreto,ladrillo,termoplastico',
            'aguaEstadoCaja'                => 'default:-1|required|in:-1,buena,sucia,mal estado',
            'aguaMaterialTapa'              => 'default:-1|required|in:-1,concreto,ladrillo,fierro,termoplastico,no tiene',
            'aguaEstadoTapa'                => 'default:-1|required|in:-1,buena,sellada,mal estado',
            'alcFechaConexion'              => 'default:1501-01-01|required|date:Y-m-d',
            'alcConexionCaracteristica'     => 'default:-1|required|in:-1,sin caja,con caja',
            'alcTipoConexion'               => 'default:-1|required|in:-1,convencional,condominial',
            'alcConexionDiametro'           => 'default:-1|required|in:-1,4,6',
            'alcDiametroRed'                => 'default:-1|required|in:-1,4,6,8,10',
            'alcMaterialConexion'           => 'default:-1|required|in:-1,pvc,fierro',
            'alcUbicacionCaja'              => 'default:-1|required|in:-1,vereda,jardin,interior casa,no tiene',
            'alcMaterialCaja'               => 'default:-1|required|in:-1,concreto,ladrillo,termoplastico',
            'alcEstadoCaja'                 => 'default:-1|required|in:-1,buena,sucia,mal estado',
            'alcDimencionCaja'              => 'default:-1|required|in:-1,70x40 cm,60x40 cm,otro',
            'alcMaterialTapa'               => 'default:-1|required|in:-1,concreto,fierro,madera,no tiene',
            'alcEstadoTapa'                 => 'default:-1|required|in:-1,buena,sellada,mal estado,no tiene',
            'alcMedidasTapa'                => 'default:-1|required|in:-1,62x32 cm,54x34 cm,53x53 cm,otro'
        ]);
        
        $validation->setAliases([
            'codigo'                        => 'codigo de contrato',
            'fechaInicio'                   => 'fecha inicio',
            'aguaFechaInstalacion'          => 'fecha de instalación (agua)',
            'aguaConexionCaracteristica'    => 'caracteristica de conexion (agua)',
            'aguaConexionDiametro'          => 'diametro de conexion (agua)',
            'aguaDiametroRed'               => 'diametro de red (agua)',
            'aguaMaterialConexion'          => 'material de conexion (agua)',
            'aguaMaterialAbrazadera'        => 'material de abrazadera (agua)',
            'aguaUbicacionCaja'             => 'ubicación de caja (agua)',
            'aguaMaterialCaja'              => 'material de caja (agua)',
            'aguaEstadoCaja'                => 'estado de caja (agua)',
            'aguaMaterialTapa'              => 'material de tapa (agua)',
            'aguaEstadoTapa'                => 'estado tapa (agua)',
            'alcFechaConexion'              => 'fecha de conexion (alcantarillado)',
            'alcConexionCaracteristica'     => 'caracteristica de conexion (alcantarillado)',
            'alcTipoConexion'               => 'tipo de conexion (alcantarillado)',
            'alcConexionDiametro'           => 'diametro de conexion (alcantarillado)',
            'alcDiametroRed'                => 'diametro de red (alcantarillado)',
            'alcMaterialConexion'           => 'material de conexion (alcantarillado)',
            'alcUbicacionCaja'              => 'ubicacion de caja (alcantarillado)',
            'alcMaterialCaja'               => 'material de caja (alcantarillado)',
            'alcEstadoCaja'                 => 'estado de caja (alcantarillado)',
            'alcDimencionCaja'              => 'dimención de caja (alcantarillado)',
            'alcMaterialTapa'               => 'material de tapa (alcantarillado)',
            'alcEstadoTapa'                 => 'estado de tapa (alcantarillado)',
            'alcMedidasTapa'                => 'medidas de tapa (alcantarillado)'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para eliminar contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesAnnular(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de contrato'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }


    /**
     * Método valida datos para suspender contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesSuspend(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de contrato'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }

    /**
     * Método valida datos para reconectar contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesReconnect(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de contrato'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }

    /**
     * Método valida datos para Inicio de Mantenimiento en Contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesOnMaintenance(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de contrato'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }

    /**
     * Método valida datos para fin de Mantenimiento en Contrato
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesEndMaintenance(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de contrato'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}