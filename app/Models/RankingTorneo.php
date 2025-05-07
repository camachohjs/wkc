<?php

// app/Models/RankingTorneo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingTorneo extends Model
{
    use HasFactory;

    protected $table = 'rankings_torneos';

    protected $fillable = [
        'nombre_torneo',
        'puntos',
        'categoria_id',
        'alumno_id',
        'maestro_id',
        'torneo_id',
        'año',
        'position',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

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

    public function posiciones()
    {
        return $this->hasMany(Position::class, 'persona_id', 'alumno_id')
                    ->orWhere('persona_id', $this->maestro_id);
    }
}
