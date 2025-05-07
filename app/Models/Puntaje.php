<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puntaje extends Model
{
    use HasFactory;

    protected $fillable = ['registro_torneo_id', 'puntaje'];

    public function registroTorneo()
    {
        return $this->belongsTo(RegistroTorneo::class);
    }
}
