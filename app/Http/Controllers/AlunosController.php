<?php

namespace App\Http\Controllers;

use App\Models\aluno;
use App\Models\presencas;
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

            return response()->json(['message' => 'Aluno Cadastrado']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function BuscarAlunos(Request $request)
    {
        $user = $request->user();
        try {
            $alunos = aluno::where('user_id', $user['id'])->get();
            return response()->json($alunos);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AtualizarAluno(Request $request)
    {
        $user = $request->user();
        try {
            $aluno = aluno::where('user_id', $user['id'])->where('id', $request['aluno']['id'])->first();
            $aluno->update([
                'nome' => $request['aluno']['nome'],
                'contato' => $request['aluno']['contato'],
                'faixa' => $request['aluno']['faixa'],
            ]);

            return response()->json('Aluno atualizado');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function DeletarAluno() {}

    public function GerarRelatorio(Request $request)
    {
        $user = $request->user();
        try {
            $aluno = aluno::where('user_id', $user['id'])->get();
            $desempenhos = $aluno->desempenho()->whereBetween('created_at', [$request['dataInicial'], $request['dataFinal']])->get();
            foreach ($desempenhos as $desempenho) {
                $desempenho->treino;
            }
            $presencas = $aluno->presencas()->whereBetween('created_at', [$request['dataInicial'], $request['dataFinal']])->get();
            return response()->json($aluno);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}




/*


{aluno: {id: 2, nome: "Joao Espanca Xota", contato: "42988224411", faixa: "Amarela"}}
aluno
: 
{id: 2, nome: "Joao Espanca Xota", contato: "42988224411", faixa: "Amarela"}
contato
: 
"42988224411"
faixa
: 
"Amarela"
id
: 
2
nome
: 
"Joao Espanca Xota"
*/