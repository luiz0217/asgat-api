<?php

namespace App\Http\Controllers;

use App\Models\turma;
use App\Models\turmaXalunos;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function CriarTurma(Request $request)
    {
        $user = $request->user();
        try {
            $turma = turma::create([
                'nome' => $request['nome'],
                'local' => $request['local'],
                'horario' => $request['horario'],
                'dia' => $request['dia'],
                'user_id' => $user['id']
            ]);

            foreach ($request['alunos'] as $value) {
                turmaXalunos::create([
                    'aluno_id' => $value['id'],
                    'turma_id' => $turma['id']
                ]);
            }

            return response()->json('Turma Criada');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AtualizarTurma(Request $request)
    {
        $user = $request->user();
        try {
            $turma = turma::with('alunos')->where('user_id',$user->id)->where('id',$request->id)->first();

            $turma->update([
                'nome' => $request['nome'],
                'local' => $request['local'],
                'horario' => $request['horario'],
                'dia' => $request['dia'],
            ]);
            
            $alunos = turmaXalunos::where('turma_id', $turma['id'])->get();
            
            foreach ($alunos as $aluno) {
                $aluno->delete();
            }
            
            foreach ($request->alunos as $value) {
                turmaXalunos::create([
                    'aluno_id' => $value,
                    'turma_id' => $turma['id']
                ]);
            }
            
            return response()->json('Turma Atualizada');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function BuscarTurmas(Request $request)
    {
        $user = $request->user();
        try {
            $turmas = turma::where('user_id',$user['id'])->get();

            return response()->json($turmas);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function BuscarTurma(Request $request)
    {
        $user = $request->user();
        try {
            $turma = turma::with('alunos')->where('user_id',$user->id)->where('id',$request->turma_id)->first();

            if(!$turma){
                return response()->json(['message' => 'Turma não encontrada'],406);
            }

            return response()->json($turma);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

/*

{
    "id": 1,
    "nome": "Turma de Competicao",
    "local": "CETT",
    "horario": "16:00:00",
    "dia": "Quarta                                                                                                                                                                                                                                                         ",
    "user_id": 1,
    "created_at": "2025-06-28T22:27:03.000000Z",
    "updated_at": "2025-06-28T22:27:03.000000Z",
    "alunos": [
        {
            "id": 1,
            "nome": "Joao Cardoso Alves",
            "idade": "2000-10-10",
            "contato": "42999999999",
            "faixa": "Verde com azul",
            "data_ingresso": "2025-06-21",
            "user_id": 1,
            "created_at": "2025-06-21T19:59:45.000000Z",
            "updated_at": "2025-06-28T23:25:10.000000Z",
            "pivot": {
                "turma_id": 1,
                "aluno_id": 1
            }
        },
        {
            "id": 2,
            "nome": "Sebastiao Camargo",
            "idade": "2005-11-20",
            "contato": "42988224411",
            "faixa": "Amarela",
            "data_ingresso": "2025-06-21",
            "user_id": 1,
            "created_at": "2025-06-21T20:21:33.000000Z",
            "updated_at": "2025-06-28T23:35:05.000000Z",
            "pivot": {
                "turma_id": 1,
                "aluno_id": 2
            }
        },
        {
            "id": 3,
            "nome": "Luiz Gustavo Padle",
            "idade": "2000-06-02",
            "contato": "42988223366",
            "faixa": "Vermelha com preta",
            "data_ingresso": "2025-06-28",
            "user_id": 1,
            "created_at": "2025-06-28T22:25:16.000000Z",
            "updated_at": "2025-06-28T23:33:47.000000Z",
            "pivot": {
                "turma_id": 1,
                "aluno_id": 3
            }
        }
    ]
}

*/