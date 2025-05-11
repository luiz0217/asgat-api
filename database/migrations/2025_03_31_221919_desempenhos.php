<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desempenhos', function (Blueprint $table) {
            $table->id();
            $table->integer('nota');
            $table->string('observacao');
            $table->integer('aula_id');
            $table->integer('aluno_id');
            $table->integer('treino_id');
            $table->integer('exercicio_id');
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('treino_id')->references('id')->on('treinos');
            $table->foreign('exercicio_id')->references('id')->on('exercicios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desempenhos');
    }
};
