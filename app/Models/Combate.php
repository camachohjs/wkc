<?php

namespace App\Models;

use App\Events\CombateFinalizado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combate extends Model
{
    protected $fillable = ['participante1_id', 'participante2_id', 'puntos_participante1', 'puntos_participante2', 'torneo_id', 'categoria_id','ganador_id', 'ronda', 'fecha_combate', 'estado', 'resultados', 'descripcion', 'orden',];

    public function participante1()
    {
        return $this->belongsTo(RegistroTorneo::class, 'participante1_id');
    }

    public function participante2()
    {
        return $this->belongsTo(RegistroTorneo::class, 'participante2_id');
    }

    public function ganador()
    {
        return $this->belongsTo(RegistroTorneo::class, 'ganador_id');
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function torneo() {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    protected static function booted()
    {
        static::updated(function ($combate) {
            if ($combate->estado === 'finalizado') {
                event(new CombateFinalizado($combate));
            }
        });
    }
}
