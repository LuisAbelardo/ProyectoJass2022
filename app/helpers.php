<?php

/**
 * Remueve espacios en blanco del inicio y final de cada
 * elemento de un array
 * @param array
 * @return array
 */
if(! function_exists('arrayTrimSpaces'))
{
    function arrayTrimSpaces($array){
        foreach($array as $key => $value){
            if (is_array($value)) {
                $array[$key] = arrayTrimSpaces($value);
            }else {
                $array[$key] = trim($value);
            }
        }
        return $array;
    }
}

/**
 * Valida si una fecha existe
 * @param string
 * @return boolean
 */
if(! function_exists('validateDate'))
{
    function validateDate($date, $format = 'Y-m-d')
    {
        $fecha = DateTime::createFromFormat($format, $date);
        
        return $fecha && $fecha->format($format) === $date;
    }
    
}

/**
 * Function obtine el nombre de mes según el número de mes obtenido como parámetro.
 * Si el codigo de mes existe se obtendra el mes de lo contrario el boleano false
 * @param string
 * @return string
 */
if(! function_exists('getMonthFromNumber'))
{
    function getMonthNameFromNumber($numeroMes)
    {
        $valueReturn = false;
        
        $meses = [['codigo' => '01', 'nombre' => 'ENERO'], ['codigo' => '02', 'nombre' => 'FEBRERO'],
            ['codigo' => '03', 'nombre' => 'MARZO'], ['codigo' => '04', 'nombre' => 'ABRIL'],
            ['codigo' => '05', 'nombre' => 'MAYO'], ['codigo' => '06', 'nombre' => 'JUNIO'],
            ['codigo' => '07', 'nombre' => 'JULIO'], ['codigo' => '08', 'nombre' => 'AGOSTO'],
            ['codigo' => '09', 'nombre' => 'SEPTIEMBRE'], ['codigo' => '10', 'nombre' => 'OCTUBRE'],
            ['codigo' => '11', 'nombre' => 'NOVIEMBRE'], ['codigo' => '12', 'nombre' => 'DICIEMBRE']
        ];
        
        foreach ($meses as $mes) {
            if ($mes['codigo'] == $numeroMes) {
                $valueReturn = $mes['nombre'];
                break;
            }
        }
        
        return  $valueReturn;
    }
    
}


/**
 * Función devuelde un token valido con longitud minima de 10
 * @param int longitud del token
 * @return boolean
 */
if(! function_exists('getToken'))
{
    function getToken($longitud)
    {
        if ($longitud < 10) {
            $longitud = 10;
        }
        
        $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
        
        return $token;
    }
    
}