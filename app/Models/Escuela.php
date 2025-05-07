<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escuela extends Model
{
    use SoftDeletes, TrimStrings;
    
    protected $fillable = [
        'nombre',
        'profesor1',
        'profesores_adicionales',
    ];

    public function getProfesoresAdicionalesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setProfesoresAdicionalesAttribute($value)
    {
        $this->attributes['profesores_adicionales'] = json_encode($value);
    }

    public function maestros()
    {
        return $this->belongsToMany(Maestro::class, 'escuela_maestro')->whereNull('deleted_at');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_escuela_maestro')->withPivot('maestro_id');
    }
}
