<?php namespace App\Validation;

class BaseValidation {

    protected $errorsMsjSpanish = [
        'required'      => 'El campo :attribute es requerido',
        'required_if'   => 'El campo :attribute es requerido',
        'min'           => 'El valor (o longitud) mínimo para :attribute es :min',
        'max'           => 'El valor (o longitud) máximo para :attribute es :max',
        'digits'        => 'El campo :attribute debe ser numérico y tener una longitud de :length',
        'email'         => 'El campo :attribute no es un email valido',
        'same'          => 'El campo :attribute no coincide',
        'in'            => 'Valor no valido para :attribute',
        'numeric'       => 'El campo :attribute debe ser numérico',
        'present'       => 'El campo :attribute debe estar presente',
        'array'         => 'El campo :attribute debe ser un array'
    ];
}