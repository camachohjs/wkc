<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory, TrimStrings;

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'fecha_evento',
        'fecha_registro',
        'direccion',
        'banner',
        'ranking',
        'premios',
        'rankings',
        'torneo_configurado',
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_torneo')->withPivot('area', 'horario','order_position');
    }

    public function registros()
    {
        return $this->hasMany(RegistroTorneo::class, 'torneo_id');
    }

    public function fusiones()
    {
        return $this->hasMany(Fusion::class);
    }

    public function combates()
    {
        return $this->hasMany(Combate::class);
    }

    public function prems()
    {
        return $this->hasMany(TorneoPremio::class, 'torneo_id',);
    }

    public function ranks()
    {
        return $this->hasMany(TorneoRanking::class, 'torneo_id',);
    }
}
