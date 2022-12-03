<?php namespace App\Libraries;


class Session {

    public static function start(){
        session_start();
    }

    public static function add($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get($key){
        return $_SESSION[$key] ?? null;
    }

    public static function addUserValue($key, $value){
        return $_SESSION['jass_user'][$key] = $value;
    }

    public static function getUserValue($key){
        return $_SESSION['jass_user'][$key] ?? null;
    }
    
    public static function unsetUser(){
        unset($_SESSION['jass_user']);
    }

    public static function unset($key){
        unset($_SESSION[$key]);
    }

    public static function destroy(){
        session_unset();
        session_destroy();
    }

    
}