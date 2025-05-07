<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoUser extends Model
{
    use HasFactory;

    protected $table = 'torneo_user';

    protected $fillable = [
        'id',
        'torneo_id',
        'user_id',
        'area',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
