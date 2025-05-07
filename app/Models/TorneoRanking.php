<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoRanking extends Model
{
    use HasFactory;
    
    protected $table = 'torneos_rankings';

    protected $fillable = ['torneo_id', 'ranking'];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}