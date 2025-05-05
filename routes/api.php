<?php

use App\Http\Controllers\AlunosController;
use App\Http\Controllers\AulasController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TreinosController;
use App\Http\Controllers\TurmaController;

Route::post('loginToken', [AuthenticatedSessionController::class, 'createToken']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function (){
    //alunos
    Route::post('criarAlunos',[AlunosController::class,'CriarAluno']);
    Route::post('atualizarAluno',[AlunosController::class,'AtualizarAluno']);
    Route::get('buscarAlunos',[AlunosController::class,'BuscarAlunos']);
    Route::get('buscarAluno',[AlunosController::class,'BuscarAluno']);
    
    //treinos
    Route::post('criarTreino',[TreinosController::class,'CriarTreino']);
    Route::post('atualizarTreino',[TreinosController::class,'AtualizarTreino']);
    Route::get('buscarTreinos',[TreinosController::class,'BuscarTreinos']);
    Route::get('buscarTreino',[TreinosController::class,'BuscarTreino']);

    //turmas
    Route::post('criarTurma',[TurmaController::class,'CriarTurma']);
    Route::get('buscarTurmas',[TurmaController::class,'BuscarTurmas']);
    Route::get('buscarTurma',[TurmaController::class,'BuscarTurma']);

    //aulas
    Route::post('criarAula',[AulasController::class,'CriarAula']);
    Route::get('buscarAulas',[AulasController::class,'BuscarAulas']);
    Route::get('buscarAula',[AulasController::class,'BuscarAula']);
    Route::post('finalizarAula',[AulasController::class,'FinalizarAula']);

});