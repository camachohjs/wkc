<?php

namespace App\Models\Traits;

trait TrimStrings
{
    protected $attributesToTrim = [
        'nombre', 'apellidos', 'email', 'fec', 'cinta', 'telefono', 'peso', 'estatura', 'genero', 'password', 'name', 
    ];

    public static function bootTrimStrings()
    {
        foreach ((new static)->attributesToTrim as $attribute) {
            static::saving(function ($model) use ($attribute) {
                if (isset($model->$attribute)) {
                    $model->$attribute = trim($model->$attribute);
                }
            });
        }
    }
}
