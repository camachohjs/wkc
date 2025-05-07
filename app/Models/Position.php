<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'id',
        'categoria_id',
        'persona_id',
        'position',
        'puntos',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function persona()
    {
        return $this->belongsTo(RankingTorneo::class);
    }
}
