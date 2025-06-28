<?php

namespace App\Http\Controllers;

use App\Models\aulas;
use App\Models\desempenho;
use App\Models\exercicios;
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

        $request->validate([
            'mes' => 'required',
        ]);

        $user = $request->user();

        $aulas = aulas::leftJoin('turmas','aulas.turma_id','=','turmas.id')
        //TODO selecionar mes
        ->where('turmas.user_id',$user['id'])
        ->where('finalizada',false)
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
        foreach ($dados['presencas'] as $presenca) {
            presencas::updateOrCreate(
                [
                    'presenca' => $presenca['presenca'],
                    'aula_id' => $dados['aula_id'],
                    'aluno_id' => $presenca['aluno_id']
                ]
            );
        }
        foreach ($dados['desempenho'] as $desempenho) {
            $ex = exercicios::where('id',$desempenho->key)->first();
            desempenho::updateOrCreate([
                'nota' => $desempenho['nota'],
                'observacao' => 'nao tem',
                'aula_id' => $request['aula_id'],
                'aluno_id' =>  $desempenho['aluno_id'],
                'treino_id' => $ex['treino_id'],
                'exercicio_id' => $desempenho->key,
            ]);
        }

        aulas::where('id',$request['aula_id'])->update([
            'finalizada' => true
        ]);
        return response()->json('Aula Finalizada');
    }
}
/*


{aula_id: 3, presencas: [{aluno_id: 1, presenca: true}], desempenho: {2: [{aluno_id: 1, nota: 6}]}}
aula_id
: 
3
desempenho
: 
{2: [{aluno_id: 1, nota: 6}]}
2
: 
[{aluno_id: 1, nota: 6}]
presencas
: 
[{aluno_id: 1, presenca: true}]
0
: 
{aluno_id: 1, presenca: true}
















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