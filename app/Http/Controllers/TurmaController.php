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
                    'aluno_id' => $value,
                    'turma_id' => $turma['id']
                ]);
            }

            return response()->json('turma criada');
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

