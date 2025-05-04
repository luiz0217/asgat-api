<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aluno extends Model
{
    protected $table = 'aluno';

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
}
