<?php namespace App\Validation;

use Rakit\Validation\Validator;

class UserValidation extends BaseValidation{
    
    /**
     * Método valida datos para registrar nuevo usuario
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function verifyRulesNew(array $data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'nombres'            => 'required',
            'apellidos'          => 'required',
            'usuario'            => 'required',
            'email'              => 'required|email',
            'password'           => 'required|min:8',
            'confirmPassword'    => 'required|same:password',
            'tipo'               => 'required|numeric'
        ]);

        $validation->setAliases([
            'password'          => 'contraseña',
            'confirmPassword'   => 'confirmación de contraseña'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar usuario
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpdate(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric', 
            'nombres'            => 'required',
            'apellidos'          => 'required',
            'usuario'            => 'required',
            'email'              => 'required|email',
            'tipo'               => 'required|numeric',
            'estado'             => 'required|numeric|in:0,1'
        ]);
        
        $validation->setAliases([
            'codigo'          => 'codigo de usuario'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para actualizar contraseña
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUpadatePassword(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric',
            'password'           => 'required|min:8',
            'confirmPassword'    => 'required|same:password'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de usuario',
            'password'          => 'contraseña',
            'confirmPassword'   => 'confirmación de contraseña'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    /**
     * Método valida datos para desbloquear usuario por limite de intentos fallidos
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesUnlockForFailedAttempts(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de usuario'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para eliminar usuario
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesDelete(array $data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'codigo'             => 'required|numeric'
        ]);
        
        $validation->setAliases([
            'codigo'            => 'codigo de usuario'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}