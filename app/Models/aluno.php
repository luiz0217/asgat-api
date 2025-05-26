<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = [
        'nome',
        'idade',
        'contato',
        'faixa',
        'data_ingresso',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'turmasxalunos', 'turma_id', 'aluno_id');
    }
}
