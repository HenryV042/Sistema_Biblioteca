<?php
// index.php

require_once '../dependencies/config.php';

// Number of records per page
$records_per_page = 20;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Query to get total number of records
$total_query = $conn->query('SELECT COUNT(*) FROM aluno');
$total_records = $total_query->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Query to get records for the current page
$sql = 'SELECT * FROM aluno LIMIT :offset, :records_per_page';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Alunos</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>

        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()">&times;</button>

                </div>

                <div class="linha"></div>
                <div class="opcoes">

                    <a href="">Cadastrar Livro</a>
                    <a href="">Cadastrar Empréstimo</a>
                    <a href="">Banco de Livros</a>
                    <a href="">Empréstimos</a>
                    <a href="">Adicionar Turma</a>
                    <a href="">Pedidos</a>
                    <a href="">Relatório</a>
                    <a href="" class="sair">Sair</a>
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
    <div class="container">
        <div class="c2">

            <button class="print-button">RELATÓRIO</button>
        </div>

        <h1 class="title">ALUNOS #ADQUIRIR SALA PELO BANCO</h1>
        <table id="userTable">
            <thead>
                <tr>
                    <th>NUMERO</th>
                    <th>MATRICULA</th>
                    <th>CPF</th>
                    <th>NOME COMPLETO DO ALUNO</th>
                    <th>CURSO</th>
                    <th>SERIE</th>
                    <th>EDITAR</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aluno['numero']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['matricula']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['curso']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['serie']); ?></td>
                        <td>
                            <button class="icon-button">
                                <i class="fa-solid fa-pen" style="color: #ffffff;"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">« Anterior</a>
            <?php endif; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Próximo »</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>