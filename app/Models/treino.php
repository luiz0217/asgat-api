<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class treino extends Model
{
    protected $table = 'treinos';

    protected $fillable = [
        'tipo', 
        'user_id'
    ];
    
    public function exercicios()
    {
        return $this->hasMany(exercicios::class);
    }
}
