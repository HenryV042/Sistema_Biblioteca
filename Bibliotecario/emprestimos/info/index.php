<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Tela informação</title>
    <link rel="stylesheet" href="Css/infom.css">
    <link rel="stylesheet" href="Css/style.css">
    <script type="text/javascript" src="script.js" defer></script>
</head>

<body>
    <!-- nav-bar -->
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="/sistema_biblioteca/Component/Menu_Nav/img/logoMenu.png" alt="Logo Menu" class="logoMenu">
                    <!-- Verifique o caminho da imagem -->
                    <button class="fechar" onclick="fecharMenu()">&times;</button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <a href="#">Cadastrar Livro</a>
                    <a href="#">Cadastrar Empréstimo</a>
                    <a href="#">Banco de Livros</a>
                    <a href="#">Empréstimos</a>
                    <a href="#">Adicionar Turma</a>
                    <a href="#">Pedidos</a>
                    <a href="#">Relatório</a>
                    <a href="#" class="sair">Sair</a>
                </div>
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="/sistema_biblioteca/Component/Menu_Nav/img/logoEEEP.png" alt="Logo EEEP"
                        class="logo_eeep">
                    <!-- Verifique o caminho da imagem -->
                    <div class="ret"></div>
                    <img src="/sistema_biblioteca/Component/Menu_Nav/img/logoNav.png" alt="Logo Library"
                        class="library">
                    <!-- Verifique o caminho da imagem -->
                </div>
            </section>
        </nav>
    </header>
    <?php
    require_once "../../dependencies/config.php";

    // Recupera a matrícula do aluno via GET
    $matricula = isset($_GET['matricula']) ? $_GET['matricula'] : '';

    // Verifica se a matrícula está definida
    if (empty($matricula)) {
        die('<div class="rot2"> <p>Matrícula não fornecida.</p></div>');
    }

    // Query para obter informações do aluno
    $alunoQuery = "SELECT a.id, a.nome_completo, a.matricula, a.curso, a.serie, t.nome_identificacao
               FROM aluno a
               JOIN turma t ON a.sala_identificacao = t.nome_identificacao AND a.curso = t.curso
               WHERE a.matricula = :matricula";

    try {
        // Prepara e executa a consulta para o aluno
        $stmt = $conn->prepare($alunoQuery);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
        $stmt->execute();
        $alunoResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$alunoResult) {
            die('<div class="rot2"> <p>Nenhum aluno encontrado com a matrícula fornecida.</p></div>');
        }

        // Query para obter informações de empréstimos do aluno
        $emprestimosQuery = "SELECT e.titulo_livro, e.numero_registro, e.data_emprestimo, e.data_devolucao, e.nome_bibliotecario, e.descricao
                         FROM emprestimos e
                         WHERE e.aluno_id = :aluno_id";

        $stmt = $conn->prepare($emprestimosQuery);
        $stmt->bindParam(':aluno_id', $alunoResult['id'], PDO::PARAM_INT);
        $stmt->execute();
        $emprestimosResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erro ao consultar o banco de dados: " . $e->getMessage());
    }

    // Fecha a conexão (não é estritamente necessário com PDO, mas pode ser feito)
    $conn = null;
    ?>

    <!-- corpo do site -->
    <main>
        <a class="voltar" href="">
            <i class="fa-solid fa-circle-arrow-left" style="color: #26a737;"></i>
        </a>

        <div class="conteiner">
            <div class="informacao-aluno">
                <h1 class="titulo">INFORMAÇÃO DO ALUNO</h1>
                <div class="linha"></div>
                <div class="rot">
                    <h3 class="">Nome: <span><?php echo htmlspecialchars($alunoResult['nome_completo']); ?></span></h3>
                    <h3 class="">Matrícula: <span><?php echo htmlspecialchars($alunoResult['matricula']); ?></span></h3>
                    <h3 class="">Curso: <span><?php echo htmlspecialchars($alunoResult['curso']); ?></span></h3>
                    <h3 class="">Turma: <span><?php echo htmlspecialchars($alunoResult['nome_identificacao']); ?></span>
                    </h3>
                    <h3 class="">Série: <span><?php echo htmlspecialchars($alunoResult['serie']); ?></span></h3>
                </div>
            </div>

            <?php if (!empty($emprestimosResult)): ?>
                <div class="informacao-emprestimo">
                    <h1 class="titulo">INFORMAÇÃO DO EMPRÉSTIMO</h1>
                    <div class="linha"></div>
                    <div class="rot">
                        <?php foreach ($emprestimosResult as $emprestimo): ?>
                            <div class="emprestimo">
                                <h3 class="">Título do Livro:
                                    <span><?php echo htmlspecialchars($emprestimo['titulo_livro']); ?></span>
                                </h3>
                                <h3 class="">Número de Registro:
                                    <span><?php echo htmlspecialchars($emprestimo['numero_registro']); ?></span>
                                </h3>
                                <h3 class="">Data do Empréstimo:
                                    <span><?php echo htmlspecialchars($emprestimo['data_emprestimo']); ?></span>
                                </h3>
                                <h3 class="">Data de Devolução:
                                    <span><?php echo htmlspecialchars($emprestimo['data_devolucao']); ?></span>
                                </h3>
                                <h3 class="">Responsável:
                                    <span><?php echo htmlspecialchars($emprestimo['nome_bibliotecario']); ?></span>
                                </h3>
                                <h3 class="">Observações: <span><?php echo htmlspecialchars($emprestimo['descricao']); ?></span>
                                </h3>
                                <hr>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="informacao-emprestimo">
                    <h1 class="titulo">INFORMAÇÃO DO EMPRÉSTIMO</h1>
                    <div class="linha"></div>
                    <div class="rot2"> <p>Este aluno não possui empréstimos.</p></div>
                   
                </div>
            <?php endif; ?>
        </div>
    </main>
    <!-- fim do corpo do site -->
</body>

</html>