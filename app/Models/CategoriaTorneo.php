<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaTorneo extends Model
{
    use HasFactory;
    protected $table = 'categoria_torneo';
    public $timestamps = false;

    protected $fillable = ['torneo_id', 'categoria_id', 'area', 'horario', 'order_position', 'ganador_id'];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function combates()
    {
        return $this->hasMany(Combate::class, 'categoria_id');
    }

    public function ganador() {
        return $this->belongsTo(RegistroTorneo::class, 'torneo_id');
    }

    public function categorias() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
