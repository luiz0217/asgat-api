<?php

namespace App\Http\Controllers;

use App\Models\exercicios;
use App\Models\treino;
use Illuminate\Http\Request;

class TreinosController extends Controller
{
    public function CriarTreino(Request $request)
    {
        $user = $request->user();
        try {
            $treino = treino::create([
                'tipo' => $request['treino'], 
                'user_id' => $user['id']
            ]);

            foreach ($request['exercicios'] as $value) {
                exercicios::create([
                    'nome' => $value,
                    'treino_id' => $treino['id'],
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AtualizarTreino(Request $request)
    {
        $user = $request->user();
        try {
            $treino = treino::where('user_id',$user['id'])->where('id',$request['treino_id'])->first();
            $treino->update([
                'tipo' => $request['treino'], 
            ]);

            foreach ($request['exercicios'] as $value) {
                exercicios::updateOrCreate([
                    'nome' => $value,
                    'treino_id' => $treino['id'],
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function BuscarTreinos(Request $request)
    {
        $user = $request->user();
        try {
            $treinos = treino::where('user_id',$user['id'])->get();

            return response()->json($treinos);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function BuscarTreino(Request $request)
    {
        $user = $request->user();
        try {
            $treino = treino::where('user_id',$user['id'])->where('id',$request['treino_id'])->first();

            $treino->exercicios;

            return response()->json($treino);

        } catch (\Throwable $th) {
            throw $th;
        }
    }


}



/*

{
    treino: bla,
    exercicios: [
        nome1,nome2
    ]
}

*/