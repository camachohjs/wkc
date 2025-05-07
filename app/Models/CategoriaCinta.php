<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaCinta extends Model
{
    protected $table = 'categoria_cinta';

    protected $fillable = ['categoria_id', 'cinta'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}

