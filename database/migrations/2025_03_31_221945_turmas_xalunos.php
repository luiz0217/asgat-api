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
        Schema::create('turmasxalunos', function (Blueprint $table) {
            $table->id();
            $table->integer('aluno_id');
            $table->integer('turma_id');
            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmasxalunos');
    }
};
