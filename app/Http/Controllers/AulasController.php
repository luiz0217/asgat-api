<?php

namespace App\Http\Controllers;

use App\Models\aulas;
use App\Models\presencas;
use App\Models\treino;
use Illuminate\Http\Request;
use App\Models\turma;
use Illuminate\Support\Facades\Redis;

class AulasController extends Controller
{
    public function criarAula(Request $request)
    {
        $user = $request->user();
        $treino = treino::where('user_id', $user['id'])->where('id', $request['treino_id'])->firt();
        $turma = turma::where('user_id', $user['id'])->where('id', $request['turma_id'])->firt();
        $dados = $request->validate([
            'nome' => 'required|string',
            'data' => 'required',
            'hora' => 'required'
        ], [
            'nome.required' => 'O campo nome precisa ser preenchido',
            'data.required' => 'A data precisa ser preenchido',
            'hora.required' => 'O horario precisa ser preenchido'
        ]);

        aulas::create(array_merge($dados, ['user_id' => $user->id, 'treino_id' => $treino['id'], 'turma_id' => $turma['id']]));

        return response()->json([
            'message' => 'Aula criada!'
        ]);
    }
    public function BuscarAulas(Request $request)
    {
        $mes = $request->query('mes');

        if (!$mes) {
            return response()->json(['error' => 'Faltando o mes']);
        }

        $aulas = aulas::whereRaw("DATE_FORMAT(data, '%Y-%m')=?", [$mes])->orderBy('data')->get();

        return response()->json($aulas);
    }

    public function FinalizarAula(Request $request)
    {
        $user = $request->user();

        $dados = $request->validate([
            'aula_id' => 'required',
            'presenca' => 'required'
        ]);

        foreach ($dados['presencas'] as $presenca) {
            presencas::updateOrCreate(
                [
                    'presenca' => $presenca['presenca'],
                    'aula_id' => $dados['aula_id'],
                    'aluno_id' => $presenca['aula_id']
                ]
            );
        }
    }
}
/*

{
    aula_id: 0,
    presencas: [
        {
            aluno_id: 0,
            presenca: true,
        }  
          
    ],
    desempenho: {
        ex1: [
                {
                    aluno_id: 0,
                    nota: 5
                },
                {
                    aluno_id: 0,
                    nota: 5
                }
            ],
        ex2: [
                {
                    aluno_id: 0,
                    nota: 5
                },
                {
                    aluno_id: 0,
                    nota: 5
                }
            ],
    }
}

*/