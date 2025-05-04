<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class desempenho extends Model
{
    protected $table = 'desempenhos';

    protected $fillable = [
        'nota',
        'observacao',
        'aula_id',
        'aluno_id',
        'treino_id',
        'exercicio_id',
    ];
}
