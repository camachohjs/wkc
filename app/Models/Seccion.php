<?php

namespace App\Models;

use App\Models\Traits\TrimStrings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory, TrimStrings;

    protected $fillable = ['nombre'];

    protected $table = 'secciones';

    public function formas()
    {
        return $this->hasMany(Forma::class);
    }
}
