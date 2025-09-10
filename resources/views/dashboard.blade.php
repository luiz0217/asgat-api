<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Taekwondo</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .logo-text {
            color: #1a202c;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }

        .title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        /* Filtros */
        .filters {
            background: rgba(26, 32, 44, 0.9);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .filters h3 {
            margin-bottom: 20px;
            color: #63b3ed;
            font-size: 1.3rem;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #cbd5e0;
            font-size: 0.9rem;
        }

        .filter-group select,
        .filter-group input {
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            background: rgba(45, 55, 72, 0.8);
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #63b3ed;
            box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.1);
        }

        .filter-btn {
            padding: 12px 24px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            height: fit-content;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        /* Cards de estatísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(26, 32, 44, 0.9);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: linear-gradient(45deg, #48bb78, #38a169);
        }

        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(45deg, #ed8936, #dd6b20);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(45deg, #4299e1, #3182ce);
        }

        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(45deg, #9f7aea, #805ad5);
        }

        .stat-value {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.8;
            font-weight: 500;
        }

        /* Gráfico */
        .chart-container {
            background: rgba(26, 32, 44, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .chart-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            text-align: center;
            color: #63b3ed;
        }

        #chartContainer {
            height: 400px;
            position: relative;
        }

        /* Loading */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #63b3ed;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .title {
                font-size: 2rem;
            }

            .filter-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 2.5rem;
            }

            .filters {
                padding: 20px;
            }

            .chart-container {
                padding: 20px;
            }

            #chartContainer {
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .logo {
                width: 80px;
                height: 80px;
            }

            .title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .stat-value {
                font-size: 2rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .filters,
        .chart-container {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        .chart-container {
            animation-delay: 0.4s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="logo-text">
                    <img src='https://asgat-training.vercel.app/logo.png' width="100" height="100">
                </div>
            </div>
            <h1 class="title">Dashboard</h1>
            <p class="subtitle">Acompanhe o desempenho dos alunos</p>
        </div>

        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Carregando dados...</p>
        </div>
      
        <div class="filters">
            <h3>🔍 Filtros</h3>
            <div class="filter-row">
                <div class="filter-group">
                    <label for="studentSelect">Selecionar Aluno:</label>
                    <select id="studentSelect">
                        <option value="all">Todos os Alunos</option>
                        @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="startDate">Data Início:</label>
                    <input type="date" id="startDate">
                </div>
                <div class="filter-group">
                    <label for="endDate">Data Fim:</label>
                    <input type="date" id="endDate">
                </div>
                <button class="filter-btn" onclick="updateDashboard()">
                    Filtrar
                </button>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value" id="totalAlunos">{{ $estatisticas->total_alunos }}</div>
                <div class="stat-label">Total de Alunos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✓</div>
                <div class="stat-value" id="totalPresencas">{{ $estatisticas->total_presencas }}</div>
                <div class="stat-label">Total de Presenças</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-value" id="maiorNota">{{ number_format($estatisticas->maior_nota, 1) }}</div>
                <div class="stat-label">Maior Nota</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value" id="mediaNota">{{ number_format($estatisticas->media_geral, 1) }}</div>
                <div class="stat-label">Média Geral</div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="chart-container">
            <h3 class="chart-title">📊 Desempenho dos Alunos (Notas x Presenças)</h3>
            <div id="chartContainer">
                <canvas id="notasChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Variáveis globais
        let chart = null;
        const dadosIniciais = @json($dadosGrafico);

        // Função para mostrar/ocultar loading
        function toggleLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }

        // Função para formatar data para YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Função para buscar dados do dashboard
        async function fetchDashboardData() {
            toggleLoading(true);
            
            const alunoId = document.getElementById('studentSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            try {
                const response = await fetch('/dashboard/filtrar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        aluno_id: alunoId,
                        start_date: startDate,
                        end_date: endDate
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                
                const data = await response.json();
                updateStats(data.estatisticas);
                updateChart(data.dadosGrafico);
            } catch (error) {
                console.error('Erro ao buscar dados:', error);
                alert('Erro ao carregar dados do dashboard.');
            } finally {
                toggleLoading(false);
            }
        }

        // Função para atualizar as estatísticas
        function updateStats(estatisticas) {
            document.getElementById('totalAlunos').textContent = estatisticas.total_alunos;
            document.getElementById('totalPresencas').textContent = estatisticas.total_presencas;
            document.getElementById('maiorNota').textContent = parseFloat(estatisticas.maior_nota).toFixed(1);
            document.getElementById('mediaNota').textContent = parseFloat(estatisticas.media_geral).toFixed(1);
            
            // Animação nos cards
            const cards = document.querySelectorAll('.stat-value');
            cards.forEach(card => {
                card.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 200);
            });
        }

        // Função para atualizar o gráfico
        function updateChart(dados) {
            const ctx = document.getElementById('notasChart').getContext('2d');
            
            if (chart) {
                chart.destroy();
            }
            
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dados.map(item => item.aluno),
                    datasets: [
                        {
                            label: 'Média das Notas',
                            data: dados.map(item => parseFloat(item.media_nota) || 0),
                            backgroundColor: 'rgba(99, 179, 237, 0.7)',
                            borderColor: '#63b3ed',
                            borderWidth: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Presenças',
                            data: dados.map(item => item.presencas || 0),
                            backgroundColor: 'rgba(72, 187, 120, 0.7)',
                            borderColor: '#48bb78',
                            borderWidth: 2,
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#cbd5e0',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#cbd5e0',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(203, 213, 224, 0.1)'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Média das Notas',
                                color: '#cbd5e0'
                            },
                            max: 10,
                            ticks: {
                                color: '#cbd5e0'
                            },
                            grid: {
                                color: 'rgba(203, 213, 224, 0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Presenças',
                                color: '#cbd5e0'
                            },
                            ticks: {
                                color: '#cbd5e0'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }

        // Função para atualizar o dashboard
        function updateDashboard() {
            fetchDashboardData();
        }

        // Inicializar dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Definir datas padrão (últimos 30 dias)
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 30);
            
            document.getElementById('startDate').value = formatDate(startDate);
            document.getElementById('endDate').value = formatDate(endDate);
            
            // Inicializar gráfico com dados iniciais
            updateChart(dadosIniciais);
            
            // Event listeners para os filtros
            document.getElementById('studentSelect').addEventListener('change', updateDashboard);
            document.getElementById('startDate').addEventListener('change', updateDashboard);
            document.getElementById('endDate').addEventListener('change', updateDashboard);
        });
    </script>
</body>
</html>