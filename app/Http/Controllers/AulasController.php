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
            'ano' => 'required',
            'mostraFinalizada' => 'required',
        ]);

        $user = $request->user();

        if ($request['mes'] == 0) {
            $aulas = aulas::leftJoin('turmas','aulas.turma_id','=','turmas.id')
            ->where('turmas.user_id',$user['id'])
            ->where('finalizada', $request['mostraFinalizada'])
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

        $aulas = aulas::leftJoin('turmas','aulas.turma_id','=','turmas.id')
        ->where('turmas.user_id',$user['id'])
        ->where('finalizada', $request['mostraFinalizada'])
        ->whereYear('aulas.dia', $request['ano'])
        ->whereMonth('aulas.dia', $request['mes'])
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
            'presencas' => 'required',
            'desempenho' => 'required',
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

        foreach ($dados['desempenho'] as $exercicio_id => $alunos) {
            if($exercicio_id != ''){
                $ex = exercicios::where('id', $exercicio_id)->first();
    
                foreach ($alunos as $infoAluno) {
                    desempenho::updateOrCreate([
                        'nota' => $infoAluno['nota'],
                        'observacao' => 'nao tem',
                        'aula_id' => $request['aula_id'],
                        'aluno_id' => $infoAluno['aluno_id'],
                        'treino_id' => $ex['treino_id'],
                        'exercicio_id' => $exercicio_id,
                    ]);
                }
            }
        }

        aulas::where('id',$request['aula_id'])->update([
            'finalizada' => true
        ]);
        return response()->json('Aula Finalizada');
    }
}