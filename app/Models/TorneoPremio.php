<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoPremio extends Model
{
    use HasFactory;

    protected $table = 'torneos_premios';

    protected $fillable = ['torneo_id', 'premio'];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}