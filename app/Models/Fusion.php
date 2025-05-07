<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fusion extends Model
{
    use HasFactory, TrimStrings;
    protected $table = 'fusiones';

    protected $fillable = ['id', 'nombre', 'descripcion', 'genero', 'peso_minimo', 'edad_minima', 'peso_maximo', 'edad_maxima', 'division', 'cinta', 'torneo_id', 'area', 'horario'];
        public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_fusion');
    }


}
