<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TrimStrings;

class TiposFormas extends Model
{
    use HasFactory, TrimStrings;
    
    protected $table = 'tipos_formas';
    protected $fillable = ['id', 'nombre', 'descripcion'];

}
