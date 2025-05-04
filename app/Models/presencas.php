<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class presencas extends Model
{
    protected $table = 'presencas';

    protected $fillable = ['presenca', 'aula_id', 'aluno_id'];
}
