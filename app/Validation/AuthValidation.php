<?php namespace App\Validation;

use Rakit\Validation\Validator;

class AuthValidation extends BaseValidation{

    
    /**
     * Método valida datos para logear usuario
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesLogin($data){

        $validator = new Validator;
            
        $validation = $validator->make($data, [
            'user'                  => 'required',
            'password'              => 'required'
        ]);

        $validation->setAliases([
            'user' => 'Usurio',
            'password' => 'Contraseña'
        ]);

        $validation->setMessages($this->errorsMsjSpanish);

        $validation->validate();

        return $validation;
    }
    
    
    /**
     * Método valida datos para crear token por olvido de password
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesCreateToken($data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'usuario'               => 'required',
            'email'                 => 'required|email'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
    
    /**
     * Método valida datos para reemplazar password
     * @param array $data
     * @return \Rakit\Validation\Validation
     */
    public function veryRulesReplacePassword($data){
        
        $validator = new Validator;
        
        $validation = $validator->make($data, [
            'usuario'                => 'required',
            'tk'                     => 'required',
            'password'               => 'required|min:8',
            'confirmPassword'        => 'required|same:password'
        ]);
        
        $validation->setAliases([
            'tk'                => 'token',
            'password'          => 'contraseña',
            'confirmPassword'   => 'confirmar contraseña'
        ]);
        
        $validation->setMessages($this->errorsMsjSpanish);
        
        $validation->validate();
        
        return $validation;
    }
    
}