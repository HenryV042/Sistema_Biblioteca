<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

$mesesPortugues = [
    'January' => 'Janeiro',
    'February' => 'Fevereiro',
    'March' => 'Março',
    'April' => 'Abril',
    'May' => 'Maio',
    'June' => 'Junho',
    'July' => 'Julho',
    'August' => 'Agosto',
    'September' => 'Setembro',
    'October' => 'Outubro',
    'November' => 'Novembro',
    'December' => 'Dezembro',
];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/styleNavbar.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()"><i
                            class="fa-solid fa-circle-arrow-left"></i></button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <a href="../registrarlivro"><i class="fa-solid fa-file"></i> Cadastrar Livro</a>
                    <a href="../cad-emprestimo"><i class="fa-solid fa-book-open-reader"></i> Cadastrar Empréstimo</a>
                    <a href="../catalogos"><i class="fa-solid fa-book-bookmark"></i> Banco de Livros</a>
                    <a href="../emprestimos"><i class="fa-brands fa-leanpub"></i> Empréstimos</a>
                    <a href="../turmas"><i class="fa-solid fa-user-plus"></i> Adicionar Turma</a>
                    <a href="../pedidos"><i class="fa-solid fa-address-book"></i> Pedidos</a>
                    <a href="../ranking"><i class="fa-solid fa-file-import"></i> Relatório</a>
                    <a href="../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i> Sair</a>
                </div>
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library" />
                </div>
            </section>
        </nav>
    </header>

    <!-- Incluindo o arquivo de conexão com o banco de dados -->
    <?php require_once '../../dependencies/config.php'; ?>

    <!-- Ranking e Filtros -->
    <div class="ranking-filtros">
        <div class="ranking"><b>Ranking</b></div>
        <div class="filtros">
            <div>
                <select id="mes-inicial" class="mes-inicialFil" name="mes-inicial">
                    <option value="">Mês Inicial</option>
                    <?php
                    // Obter meses distintos do banco de dados
                    $mesQuery = $conn->query("SELECT DISTINCT MONTH(data_emprestimo) AS mes FROM emprestimos");
                    $meses = $mesQuery->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($meses as $mes) {
                        $mesNome = date('F', mktime(0, 0, 0, $mes['mes'], 10));
                        echo '<option value="' . str_pad($mes['mes'], 2, '0', STR_PAD_LEFT) . '">' . $mesesPortugues[$mesNome] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <select id="mes-final" name="mes-final" class="mes-finalFil">
                    <option value="">Mês Final</option>
                    <?php
                    // Repetir para o mês final
                    foreach ($meses as $mes) {
                        $mesNome = date('F', mktime(0, 0, 0, $mes['mes'], 10));
                        echo '<option value="' . str_pad($mes['mes'], 2, '0', STR_PAD_LEFT) . '">' . $mesesPortugues[$mesNome] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button id="aplicar-filtros" class="relatorio"> Aplicar Filtros</button>
        </div>
    </div>

    <div class="container-filtro-cursos">
        <select id="filtro-cursos" name="cursos" class="filtro-cursos">
            <option value="">SÉRIE</option>
            <option value="enfermagem">Enfermagem</option>
            <option value="informatica">Informática</option>
            <option value="comercio">Comércio</option>
            <option value="administracao">Administração</option>
        </select>
    </div>

    <div class="graficos">
        <div class="grafico-curso">
            <div class="teste" id="chart_livros"></div>
        </div>

        <div class="grafico-livros">
            <div class="teste" id="chart_cursos"></div>
        </div>
    </div>

    <!-- PHP para exibir os rankings -->
    <?php
    try {
        
        // Ranking de cursos que mais leram
        $queryCursos = $conn->query("
        SELECT t.nome_identificacao AS turma, COUNT(e.id) AS total_leituras
        FROM emprestimos e
        JOIN aluno a ON e.aluno_id = a.id
        JOIN turma t ON a.curso = t.curso AND a.serie = t.serie
        GROUP BY t.nome_identificacao
        ORDER BY total_leituras DESC
        LIMIT 5
        ");

        $rankingCursos = $queryCursos->fetchAll(PDO::FETCH_ASSOC);

        // Ranking dos livros mais lidos
        $queryLivros = $conn->query("
        SELECT e.titulo_livro AS livro, COUNT(e.id) AS total_leituras
        FROM emprestimos e
        GROUP BY e.titulo_livro
        ORDER BY total_leituras DESC
        LIMIT 5
        ");
        $rankingLivros = $queryLivros->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    ?>

    <script type="text/javascript">
        // Carregar o Google Charts
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Dados para o gráfico de livros
            var livrosData = google.visualization.arrayToDataTable([
                ['Livro', 'Total Leituras'],
                <?php
                $livrosData = [];
                foreach ($rankingLivros as $livro) {
                    $livrosData[] = "['" . addslashes($livro['livro']) . "', " . $livro['total_leituras'] . "]";
                }
                echo implode(",", $livrosData);
                ?>
            ]);

            var livrosOptions = {
                title: 'Ranking de Livros',
                pieHole: 0.4,
            };

            var livrosChart = new google.visualization.PieChart(document.getElementById('chart_livros'));
            livrosChart.draw(livrosData, livrosOptions);

            // Dados para o gráfico de cursos
            var cursosData = google.visualization.arrayToDataTable([
                ['Turma', 'Total Leituras'],
                <?php
                $cursosData = [];
                foreach ($rankingCursos as $curso) {
                    $cursosData[] = "['" . addslashes($curso['turma']) . "', " . $curso['total_leituras'] . "]";
                }
                echo implode(",", $cursosData);
                ?>
            ]);

            var cursosOptions = {
                title: 'Ranking de Cursos',
                pieHole: 0.4,
            };

            var cursosChart = new google.visualization.PieChart(document.getElementById('chart_cursos'));
            cursosChart.draw(cursosData, cursosOptions);
        }

        // Aplicar filtros e atualizar gráficos
        document.getElementById('aplicar-filtros').addEventListener('click', function () {
            const mesInicial = document.getElementById('mes-inicial').value;
            const mesFinal = document.getElementById('mes-final').value;
            const curso = document.getElementById('filtro-cursos').value;

            fetch('get_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    mes_inicial: mesInicial,
                    mes_final: mesFinal,
                    curso: curso
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Atualizar gráficos com os novos dados
                    var livrosData = google.visualization.arrayToDataTable([
                        ['Livro', 'Total Leituras'],
                        ...data.livros.map(livro => [livro.livro, livro.total_leituras])
                    ]);

                    var livrosOptions = {
                        title: 'Ranking de Livros',
                        pieHole: 0.4,
                    };

                    var livrosChart = new google.visualization.PieChart(document.getElementById('chart_livros'));
                    livrosChart.draw(livrosData, livrosOptions);

                    var cursosData = google.visualization.arrayToDataTable([
                        ['Turma', 'Total Leituras'],
                        ...data.cursos.map(curso => [curso.turma, curso.total_leituras])
                    ]);

                    var cursosOptions = {
                        title: 'Ranking de Cursos',
                        pieHole: 0.4,
                    };

                    var cursosChart = new google.visualization.PieChart(document.getElementById('chart_cursos'));
                    cursosChart.draw(cursosData, cursosOptions);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <script src="scripts.js"></script>
</body>

</html>
