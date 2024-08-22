<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}


require_once '../../dependencies/config.php';


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cursoFilter = isset($_GET['curso']) ? trim($_GET['curso']) : '';
$serieFilter = isset($_GET['serie']) ? trim($_GET['serie']) : '';

try {
    // Consulta base com junção das tabelas aluno e emprestimos pela matrícula
    $query = "
        SELECT
            e.id AS id,
            a.nome_completo AS aluno_nome,
            e.matricula AS aluno_matricula,
            e.titulo_livro,
            e.numero_registro,
            a.curso,
            a.serie,
            CASE
                WHEN e.data_rascunho IS NOT NULL THEN DATEDIFF(NOW(), e.data_rascunho)
                ELSE DATEDIFF(NOW(), e.data_emprestimo)
            END AS dias,
            CASE
                WHEN e.data_rascunho IS NOT NULL THEN DATE_FORMAT(e.data_rascunho + INTERVAL 7 DAY, '%d/%m')
                ELSE DATE_FORMAT(e.data_emprestimo + INTERVAL 7 DAY, '%d/%m')
            END AS prazo,
            e.status
        FROM emprestimos e
        JOIN aluno a ON e.matricula = a.matricula
        WHERE e.data_devolucao IS NULL
    ";

    // Adiciona filtros de curso e série se fornecidos
    $conditions = [];
    if ($search) {
        $conditions[] = "(a.nome_completo LIKE :search OR e.matricula LIKE :search)";
    }
    if ($cursoFilter && $cursoFilter != 'TODOS') {
        $conditions[] = "a.curso = :curso";
    }
    if ($serieFilter && $serieFilter != 'TODOS') {
        $conditions[] = "a.serie = :serie";
    }

    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);

    // Vincula os parâmetros de busca
    if ($search) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }
    if ($cursoFilter && $cursoFilter != 'TODOS') {
        $stmt->bindValue(':curso', $cursoFilter);
    }
    if ($serieFilter && $serieFilter != 'TODOS') {
        $stmt->bindValue(':serie', $serieFilter);
    }

    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
        header('Content-Type: application/json');
        echo json_encode($emprestimos);
        exit;
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}

// Limpa o buffer de saída
ob_end_flush();
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/indextb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
        <style>
            .pendencia {
    display: flex;
    background-color: #FF0000; /* Cor de fundo vermelha */
    color: #fff; 
    border: none;
    border-radius: 10px; 
    width: 7.5vw;
    height: 5.5vh;
    text-decoration: none;
 }
 
 .pendencia:hover {
    background-color: #cc0000; /* Cor de fundo ao passar o mouse (vermelho mais escuro) */
 }
        </style>
</head>

<body>

    <header>

        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()"><i class="fa-solid fa-circle-arrow-left"></i></button>
                        
                </div>
                    
                <div class="linha"></div>
                <div class="opcoes">
        
                    <a href="../registrarlivro"><i class="fa-solid fa-file"></i>    Cadastrar Livro</a>
                    <a href="../cad-emprestimo"><i class="fa-solid fa-book-open-reader"></i>    Cadastrar Empréstimo</a>
                    <a href="../catalogo-bibliotecario"><i class="fa-solid fa-book-bookmark"></i>    Banco de Livros</a>
                    <a href="../emprestimos"><i class="fa-brands fa-leanpub"></i>    Empréstimos</a>
                    <a href="../turmas"><i class="fa-solid fa-user-plus"></i>    Adicionar Turma</a>
                    <a href="../pedidos"><i class="fa-solid fa-address-book"></i>    Pedidos</a>
                    <a href="../ranking"><i class="fa-solid fa-medal"></i>    Ranking</a>
                    <a href="../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i>    Sair</a>
                </div>
                    
            </aside>
            <section id="principal">
                <span onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img  src="img/logoEEEP.png" alt="logo" class="logo_eeep"/>
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library"/>
                </div>
        
            </section>
        </nav>
        
    </header>
    
    <div class="container">
        <div class="cabecario">
            <h1 class="title">EMPRÉSTIMOS</h1>
            <div class="search-container">
                <input type="text" id="search-box" class="search-box" placeholder="Pesquisar aluno...">
            </div>

            <a href="pendencias/" class="print-button pendencia">PENDÊNCIAS</a>


            <div class="filtros">
                <div>
                    <select id="turma-filter" class="turma-filter" name="turma-filter">
                        <option value="">SERIE</option>
                        <option value="1">1º ANO</option>
                        <option value="2">2º ANO</option>
                        <option value="3">3º ANO</option>
                        <option value="TODOS">TODOS</option>
                    </select>
                </div>
                <div>
                    <select id="curso-filter" class="curso-filter" name="curso-filter">
                        <option value="">CURSO</option>
                        <option value="Enfermagem">ENFERMAGEM</option>
                        <option value="Informática">INFORMÁTICA</option>
                        <option value="Comércio">COMÉRCIO</option>
                        <option value="Administração">ADMINISTRAÇÃO</option>
                        <option value="TODOS">TODOS</option>
                    </select>
                </div>
            </div>
            <button class="print-button"><i class="fa-solid fa-print"></i> RELATÓRIO</button>
        </div>
        <table id="booksTable">
            <thead>
                <tr>
                    <th>NOME DO ALUNO</th>
                    <th>MATRÍCULA</th>
                    <th>NOME DO LIVRO</th>
                    <th>DIAS</th>
                    <th>PRAZO</th>
                    <th>MAIS INFORMAÇÕES</th>
                    <th>RENOVAR</th>
                    <th>APAGAR</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Os dados da tabela serão preenchidos pelo JavaScript -->
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            const searchBox = document.getElementById('search-box');
            const tableBody = document.getElementById('table-body');
            const turmaFilter = document.getElementById('turma-filter');
            const cursoFilter = document.getElementById('curso-filter');

            function fetchData() {
                const query = searchBox.value;
                const serie = turmaFilter.value;
                const curso = cursoFilter.value;

                fetch(`index.php?ajax=true&serie=${encodeURIComponent(serie)}&curso=${encodeURIComponent(curso)}&search=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function updateTable(emprestimos) {
                tableBody.innerHTML = '';

                if (emprestimos.length > 0) {
                    emprestimos.forEach(emprestimo => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="aluno-nome">${emprestimo.aluno_nome}</td>
                            <td class="aluno-matricula">${emprestimo.aluno_matricula}</td>
                            <td>${emprestimo.titulo_livro}</td>
                            <td class="days">${emprestimo.dias}</td>
                            <td>${emprestimo.prazo}</td>
                            <td>
                                <a href="info/index.php?matricula=${encodeURIComponent(emprestimo.aluno_matricula)}&id=${encodeURIComponent(emprestimo.id)}" class="icon-button">
                                    <i class="fa-solid fa-file-lines"></i>
                                </a>
                            </td>
                            <td>
                                <a href="renew.php?id=${encodeURIComponent(emprestimo.id)}" class="icon-button">
                                    <i class="fa-regular fa-calendar"></i>
                                </a>
                            </td>
                            <td>
                                <a href="devolucao.php?id=${encodeURIComponent(emprestimo.id)}" class="icon-button">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="8">Nenhum registro encontrado.</td>';
                    tableBody.appendChild(row);
                }
            }

            searchBox.addEventListener('input', fetchData);
            turmaFilter.addEventListener('change', fetchData);
            cursoFilter.addEventListener('change', fetchData);

            fetchData(); // Carrega os dados iniciais
        });
    </script>

    <script src="scripts.js"></script>
</body>

</html>