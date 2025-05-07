<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes, TrimStrings;

    protected $table = 'categorias';

    protected $fillable = ['id', 'categoria_padre_id', 'forma_id', 'nombre', 'descripcion', 'genero', 'peso_minimo', 'edad_minima', 'peso_maximo', 'edad_maxima', 'division', 'cinta', 'fusion_id'];

    public function forma()
    {
        return $this->belongsTo(Forma::class);
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'categoria_torneo')->withPivot('area', 'horario', 'order_position');
    }

    public function registrosTorneo()
    {
        return $this->hasMany(RegistroTorneo::class, 'categoria_id');
    }

    public function fusiones()
    {
        return $this->belongsToMany(Fusion::class, 'categoria_fusion');
    }

    public function participantes()
    {
        return $this->hasMany(RegistroTorneo::class, 'categoria_id');
    }

    public function cintas()
    {
        return $this->hasMany(CategoriaCinta::class , 'categoria_id');
    }

    public function categoriaPadre()
    {
        return $this->belongsTo(Categoria::class, 'categoria_padre_id');
    }

    public function subCategorias()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }

}
