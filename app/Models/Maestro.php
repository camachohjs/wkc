<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maestro extends Model
{
    use HasFactory, SoftDeletes, TrimStrings;

    protected $table = 'maestros';

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'fec',
        'cinta',
        'telefono',
        'peso',
        'estatura',
        'genero',
        'password',
        'user_id',
        'foto',
        'nacionalidad',
    ];

    protected $appends = ['codigo_bandera'];

    public function getCodigoBanderaAttribute()
    {
        $map = [
                'Mexico' => 'mx',
                'Argentina' => 'ar', 
                'Bolivia' => 'bo', 
                'Chile' => 'cl', 
                'Colombia' => 'co',
                'Costa Rica' => 'cr',
                'Cuba' => 'cu', 
                'Republica Dominicana' => 'do',
                'Ecuador' => 'ec', 
                'El Salvador' => 'sv',
                'Guatemala' => 'gt', 
                'Honduras' => 'hn', 
                'Nicaragua' => 'ni',
                'Panama' => 'pa', 
                'Paraguay' => 'py',
                'Peru' => 'pe', 
                'Espana' => 'es',
                'Uruguay' => 'uy',
                'Venezuela' => 've',
                'Estados Unidos' => 'us',
                'Canada' => 'ca',
                'Brasil' => 'br',
        ];

        return $map[$this->nacionalidad] ?? 'unknown';
    }

    public function getEdadAttribute()
    {
        $fechaNacimiento = Carbon::parse($this->attributes['fec']);
        $currentYear = Carbon::now()->year;
        $birthYear = $fechaNacimiento->year;
        $añoSiguiente = Carbon::now()->addYear();

        // Comprobar si aún no hemos llegado al 1 de enero del próximo año
        if (Carbon::now()->lt(Carbon::createFromDate($añoSiguiente, 1, 1))) {
            return $currentYear - $birthYear - 1;
        } else {
            return $currentYear - $birthYear;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function escuelas()
    {
        return $this->belongsToMany(Escuela::class, 'escuela_maestro');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_escuela_maestro')->withPivot('escuela_id');
    }

    public function getTipoAttribute() {
        return 'maestro';
    }

    public function registros()
    {
        return $this->hasMany(RegistroTorneo::class, 'maestro_id');
        //es para ver en donde se han registrado
    }
}
