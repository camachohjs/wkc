<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kata extends Model
{
    use HasFactory;

    protected $table = 'katas';

    protected $fillable = [
        'participante_id', 
        'torneo_id', 
        'categoria_id', 
        'estado', 
        'order_position', 
        'calificacion_1', 
        'calificacion_2', 
        'calificacion_3', 
        'calificacion_nueva_1', 
        'calificacion_nueva_2', 
        'calificacion_nueva_3', 
        'ronda', 
        'asistencia',  
        'total', 
        'total_nuevo', 
        'tiempo'
    ];

    public function participante()
    {
        return $this->belongsTo(RegistroTorneo::class, 'participante_id');
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function torneo() {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }
}
