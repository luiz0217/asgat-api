<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aulas extends Model
{
    protected $table = 'aulas';

    protected $fillable = [
        'dia',
        'turma_id',
        'treino_id',
        'user_id',
    ];

    
    public function turma()
    {
        return $this->belongsTo(turma::class);
    }

    public function treino()
    {
        return $this->belongsTo(treino::class);
    }
}
