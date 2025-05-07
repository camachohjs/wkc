<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadosTorneo extends Model
{
    use HasFactory;

    protected $table = 'resultados_torneo';

    protected $fillable = [
        'torneo_id',
        'categoria_id',
        'participante_id',
        'posicion',
    ];

    public function participante()
    {
        return $this->belongsTo(RegistroTorneo::class, 'participante_id');
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
