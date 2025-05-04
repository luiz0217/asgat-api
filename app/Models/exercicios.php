<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class exercicios extends Model
{
    protected $table = 'exercicios';

    protected $fillable = [
        'nome',
        'treino_id',
    ];
}
