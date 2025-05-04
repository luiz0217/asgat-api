<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class turmaXalunos extends Model
{
    use HasFactory;

    protected $table = 'turmasxalunos';

    protected $fillable = ['aluno_id','turma_id'];

    public $timestamps = true;

    
    public function aluno()
    {
        return $this->belongsTo(aluno::class);
    }

    public function user()
    {
        return $this->belongsTo(turma::class);
    }

}
