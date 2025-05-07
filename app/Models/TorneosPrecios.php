<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\TrimStrings;

class TorneosPrecios extends Model
{
    use HasFactory, TrimStrings;

    protected $table = 'torneos_precios';
    protected $fillable = ['id', 'torneo_id','tipos_formas_id', 'fecha', 'costo_pre_registro', 'costo_registro'];
    
}
