<?php namespace App\Libraries;

class LogMonolog {
    
    /*
     * Array para guardar las dependencias según donde Monolog registrará los log.
     * Esto es porque Monolog puede usarse para guardar log en texto plano (archivos)
     * o base de datos
     */
    private static $dependencias = [];
    
    /*
     * Método para registrar las dependencias (agrupadas por una 'llave')
     * que se usaran para el registro de logs
     * @param string 'llave' de la depencias a registrar
     * @param callbak función que contiene las depencias a usar
     */
    public static function set(string $key, $func){
        self::$dependencias[$key] = $func;
    }
    
    /*
     * Método devuelve una depencia registrada
     * @param string clave de la depencia
     * @return \Monolog\Logger
     */
    public static function get(string $key){
        return self::$dependencias[$key]();
    }
    
    /*
     * Método ejecuta las depencias registradas para guardar 
     * los log en texto plano
     */
    public static function logFile(){
        self::set('loggerfile', function(){
            $log = new \Monolog\Logger('jass');
            $log->pushHandler(new \Monolog\Handler\StreamHandler(APP_PATH.'/log/'.date('dmY').'.log'));
            return $log;
        });
    }
}