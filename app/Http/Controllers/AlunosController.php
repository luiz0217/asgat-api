<?php

namespace App\Http\Controllers;

use App\Models\aluno;
use Illuminate\Http\Request;

class AlunosController extends Controller
{
    public function CriarAluno(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'idade' => 'required',
            'contato' => 'required|numeric|integer',
            'faixa' => 'required|string',
            'data_ingresso' => 'required',
        ]);

        $user = $request->user();
        try {
            aluno::create([
                'nome' => $request['nome'],
                'idade' => $request['idade'],
                'contato' => $request['contato'],
                'faixa' => $request['faixa'],
                'data_ingresso' => $request['data_ingresso'],
                'user_id' => $user['id'],
            ]);

            return response()->json(['message' => 'aluno cadastrado']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function BuscarAlunos(Request $request)
    {
        $user = $request->user();
        try {
            $alunos = aluno::where('user_id',$user['id'])->get();
            return response()->json($alunos);            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AtualizarAluno(Request $request)
    {
        $user = $request->user();
        try {
            $aluno = aluno::where('user_id',$user['id'])->where('id',$request['id'])->first();
            $aluno->update([
                'nome' => $request['nome'],
                'idade' => $request['idade'],
                'contato' => $request['contato'],
                'faixa' => $request['faixa'],
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function DeletarAluno()
    {
        
    }
}
