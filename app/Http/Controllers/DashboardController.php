<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
       
        $alunos = DB::select('SELECT id, nome FROM alunos  ORDER BY nome');
        
        $estatisticas = DB::select('
           SELECT 
                COUNT(DISTINCT a.id) as total_alunos,
                COUNT(p.id) as total_presencas,
                COALESCE(MAX(n.nota), 0) as maior_nota,
                COALESCE(AVG(n.nota), 0) as media_geral
            FROM alunos a
            LEFT JOIN presencas p ON a.id = p.aluno_id
            LEFT JOIN desempenhos n ON a.id = n.aluno_id
        ');
        

        $dadosGrafico = DB::select('
             SELECT 
                a.nome as aluno,
                COUNT(p.id) as presencas,
                COALESCE(AVG(n.nota), 0) as media_nota
            FROM alunos a
            LEFT JOIN presencas p ON a.id = p.aluno_id
            LEFT JOIN desempenhos n ON a.id = n.aluno_id
            GROUP BY a.id, a.nome
            ORDER BY media_nota DESC
            LIMIT 10
        ');
        
        return view('dashboard', [
            'alunos' => $alunos,
            'estatisticas' => $estatisticas[0] ?? (object)[
                'total_alunos' => 0,
                'total_presencas' => 0,
                'maior_nota' => 0,
                'media_geral' => 0
            ],
            'dadosGrafico' => $dadosGrafico
        ]);
    }
    
    public function filtrar(Request $request)
    {
        $alunoId = $request->input('aluno_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
      
        $queryEstatisticas = '
             SELECT 
                COUNT(DISTINCT a.id) as total_alunos,
                COUNT(p.id) as total_presencas,
                COALESCE(MAX(n.nota), 0) as maior_nota,
                COALESCE(AVG(n.nota), 0) as media_geral
            FROM alunos a
            LEFT JOIN presencas p ON a.id = p.aluno_id
            LEFT JOIN desempenhos n ON a.id = n.aluno_id
        ';
        

        $queryGrafico = '
             SELECT 
                a.nome as aluno,
                COUNT(p.id) as presencas,
                COALESCE(AVG(n.nota), 0) as media_nota
            FROM alunos a
            LEFT JOIN presencas p ON a.id = p.aluno_id
            LEFT JOIN desempenhos n ON a.id = n.aluno_id
         
        ';
        
        $params = [];
        $whereConditions = [];
        
       
        if ($alunoId && $alunoId !== 'all') {
            $whereConditions[] = 'a.id = ?';
            $params[] = $alunoId;
        }
        
        if ($startDate && $endDate) {
            $whereConditions[] = '(p.created_at IS NULL OR p.created_at BETWEEN ? AND ?)';
            $whereConditions[] = '(n.created_at IS NULL OR n.created_at BETWEEN ? AND ?)';
            $params[] = $startDate;
            $params[] = $endDate;
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
    
        if (!empty($whereConditions)) {
            $queryEstatisticas .= ' WHERE ' . implode(' AND ', $whereConditions);
            $queryGrafico .= ' WHERE ' . implode(' AND ', $whereConditions);
        }
        
        $queryGrafico .= ' GROUP BY a.id, a.nome ORDER BY media_nota DESC LIMIT 10';
        
       
        $estatisticas = DB::select($queryEstatisticas, $params);
        $dadosGrafico = DB::select($queryGrafico, $params);
        
        return response()->json([
            'estatisticas' => $estatisticas[0] ?? (object)[
                'total_alunos' => 0,
                'total_presencas' => 0,
                'maior_nota' => 0,
                'media_geral' => 0
            ],
            'dadosGrafico' => $dadosGrafico
        ]);
    }
}