<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class turma extends Model
{
    use HasFactory;

    protected $table = 'turmas';

    protected $fillable = [
        'nome',
        'local',
        'horario',
        'dia',
        'user_id'
    ];

    
    public function user ()
    {
        return $this->belongsTo(User::class); 
    }

    public function alunos()
    {
        return $this->belongsToMany(aluno::class, 'turmasxalunos', 'turma_id', 'aluno_id');
    }
}
