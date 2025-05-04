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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->integer('turma_id');
            $table->integer('treino_id');
            $table->integer('user_id');
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->foreign('treino_id')->references('id')->on('treinos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
