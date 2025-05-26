<?php

namespace App\Http\Controllers;

use App\Models\aulas;
use App\Models\presencas;
use App\Models\treino;
use Illuminate\Http\Request;
use App\Models\turma;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class AulasController extends Controller
{
    public function CriarAula(Request $request)
    {
        $user = $request->user();
        $treino = treino::where('user_id', $user['id'])->where('id', $request['treino_id'])->first();
        $turma = turma::where('user_id', $user['id'])->where('id', $request['turma_id'])->first();
        $dados = $request->validate([
            'dia' => 'required',
            'hora' => 'required'
        ], [
            'dia.required' => 'A data precisa ser preenchido',
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
        $user = $request->user();
        if (!$mes) {
            return response()->json(['error' => 'Faltando o mes']);
        }

        $aulas = aulas::leftJoin('turmas','aulas.turma_id','=','turmas.id')
        //TODO selecionar mes
        ->where('turmas.user_id',$user['id'])
        ->select([
            'aulas.*',
            'turmas.nome',
            'turmas.local',
            'turmas.horario',
            ])
        ->orderBy('turmas.dia')
        ->get();

        return response()->json($aulas);
    }

    public function BuscarAula(Request $request)
    {

    $user = $request->user();
    
    $aula = aulas::where('user_id',$user['id'])->where('id',$request['aula_id'])->first();
    
    //return response()->json($aula->turma->alunos);
    $aula->turma;
    $aula->turma->alunos;
    $aula->treino;
    $aula->treino->exercicios;

    /*
    $aula = aulas::with(['turma.alunos', 'treino.exercicios'])
        ->where('user_id', $user->id)
        ->where('id', $request->aula_id)
        ->first();
    
    */
    
    return response()->json($aula);
    

        
    }

    public function FinalizarAula(Request $request)
    {
        $user = $request->user();

        $dados = $request->validate([
            'aula_id' => 'required',
            'presencas' => 'required'
        ]);
        //return response()->json($dados['presencas']);
        foreach ($dados['presencas'] as $presenca) {
            //return response()->json($presenca);
            presencas::updateOrCreate(
                [
                    'presenca' => $presenca['presenca'],
                    'aula_id' => $dados['aula_id'],
                    'aluno_id' => $presenca['aluno_id']
                ]
            );
            return response()->json('Aula Finalizada');
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