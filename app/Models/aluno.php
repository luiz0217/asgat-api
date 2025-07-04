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
        return $this->belongsToMany(Turma::class, 'turmasxalunos',  'aluno_id','turma_id');
    }

    public function desempenho()
    {
        return $this->hasMany(desempenho::class);
    }

    public function presencas()
    {
        return $this->hasMany(presencas::class);
    }
}
