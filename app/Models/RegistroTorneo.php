<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroTorneo extends Model
{
    use HasFactory, SoftDeletes, TrimStrings;

    protected $table = 'registros_torneos';

    protected $fillable = [
        'alumno_id',
        'maestro_id',
        'torneo_id',
        'cinta',
        'peso',
        'estatura',
        'genero', 
        'nombre',
        'apellidos',
        'email',
        'fec',
        'telefono',
        'categoria_id',
        'puntaje',
        'order_position',
        'check_pago',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function maestro()
    {
        return $this->belongsTo(Maestro::class, 'maestro_id');
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function katas()
    {
        return $this->hasMany(Kata::class, 'participante_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function puntajes()
    {
        return $this->hasMany(Puntaje::class);
    }
}