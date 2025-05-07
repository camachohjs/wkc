<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forma extends Model
{
    use HasFactory, SoftDeletes, TrimStrings;

    protected $table = 'formas';

    protected $fillable = ['nombre', 'seccion_id', 'tipos_formas_id'];

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function tiposForma()
    {
        return $this->belongsTo(TiposFormas::class, 'tipos_formas_id');
    }
}
