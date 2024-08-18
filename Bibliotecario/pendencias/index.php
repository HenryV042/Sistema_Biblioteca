<?php
require_once '../../dependencies/config.php';

try {
    // Consulta para obter os dados dos empréstimos, considerando data_rascunho e data_emprestimo
    $query = "
    SELECT
        a.nome_completo AS aluno_nome,
        e.matricula AS aluno_matricula,
        e.titulo_livro,
        e.numero_registro,
        a.curso,
        a.serie,
        -- Calcula o número de dias com base na existência de data_rascunho ou data_emprestimo
        CASE
            -- Se data_rascunho não for nula, calcula a diferença de dias a partir de data_rascunho
            WHEN e.data_rascunho IS NOT NULL THEN DATEDIFF(NOW(), e.data_rascunho)
            -- Caso contrário, calcula a diferença de dias a partir de data_emprestimo
            ELSE DATEDIFF(NOW(), e.data_emprestimo)
        END AS dias,
        -- Calcula o prazo baseado na existência de data_rascunho ou data_emprestimo
        CASE
            -- Se data_rascunho não for nula, calcula o prazo adicionando 7 dias a data_rascunho
            WHEN e.data_rascunho IS NOT NULL THEN DATE_FORMAT(e.data_rascunho + INTERVAL 7 DAY, '%d/%m')
            -- Caso contrário, calcula o prazo adicionando 7 dias a data_emprestimo
            ELSE DATE_FORMAT(e.data_emprestimo + INTERVAL 7 DAY, '%d/%m')
        END AS prazo,
        e.status
    FROM emprestimos e
    JOIN aluno a ON e.matricula = a.matricula  -- Junção pela matrícula
    WHERE e.data_devolucao IS NULL
      AND (
          -- Se data_rascunho não for nula, o número de dias calculado a partir de data_rascunho deve ser maior que 7
          (e.data_rascunho IS NOT NULL AND DATEDIFF(NOW(), e.data_rascunho) > 7)
          -- Se data_rascunho for nula, o número de dias calculado a partir de data_emprestimo deve ser maior que 7
          OR
          (e.data_rascunho IS NULL AND DATEDIFF(NOW(), e.data_emprestimo) > 7)
      )
";


    $stmt = $conn->prepare($query);
    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/indextb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()"> <i
                            class="fa-solid fa-chevron-left"></i></button>
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

    <div class="container">
        <div class="cabecario">
            <h1 class="title">ALUNOS COM PENDÊNCIAS</h1>
            <button class="print-button">RELATÓRIO</button>
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
            <tbody>
                <?php foreach ($emprestimos as $emprestimo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emprestimo['aluno_nome']); ?></td>
                        <td><?php echo htmlspecialchars($emprestimo['aluno_matricula']); ?></td>
                        <td><?php echo htmlspecialchars($emprestimo['titulo_livro']); ?></td>
                        <td class="days"><?php echo htmlspecialchars($emprestimo['dias']); ?></td>
                        <td><?php echo htmlspecialchars($emprestimo['prazo']); ?></td>
                        <td>
                            <a href="info/index.php?matricula=<?php echo urlencode($emprestimo['aluno_matricula']); ?>&id=<?php echo urlencode($emprestimo['numero_registro']); ?>"
                                class="icon-button">
                                <i class="fa-solid fa-file-lines"></i>
                            </a>
                        </td>

                        <td><button class="icon-button"><i class="fa-regular fa-calendar"></i></button></td>
                        <td><button class="icon-button"><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>