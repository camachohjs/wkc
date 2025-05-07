<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
    use HasFactory, SoftDeletes, TrimStrings;
    protected $table = 'alumnos';

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
        'mayor_de_edad',
        'cinta_negra',
    ];

    protected $appends = ['codigo_bandera'];

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

    public function getTipoAttribute() {
        return 'alumno';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function escuelas()
    {
        return $this->belongsToMany(Escuela::class, 'alumno_escuela_maestro');
    }

    public function maestros()
    {
        return $this->belongsToMany(Maestro::class, 'alumno_escuela_maestro')->withPivot('escuela_id');
    }

    public function profesores()
    {
        return $this->escuelas->map->only(['profesor1', 'profesor2', 'profesor3'])->flatten()->filter();
    }

    public function registros()
    {
        return $this->hasMany(RegistroTorneo::class, 'alumno_id');
        //es para ver en donde se han registrado
    }
}
