<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep"/>
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library"/>
                </div>
            </section>
        </nav>
    </header>

    <!-- PARTE DA CONFIRMAÇÃO -->
    <div class="container-pedidos">
        <div class="titulo-pedidos">PEDIDOS</div>
        <?php
        require_once '../../dependencies/config.php'; // Incluindo o arquivo de conexão

        try {
            // Executar a consulta com junção
            $sql = 'SELECT p.*, a.nome_completo, LEFT(a.sala_identificacao, 4) AS sala_digitos
                    FROM pedidos p
                    LEFT JOIN aluno a ON p.matricula = a.matricula';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            // Buscar todos os resultados
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pedidos as $pedido) {
                $nome = htmlspecialchars($pedido['nome_completo']) . ' - ' . htmlspecialchars($pedido['sala_digitos']);
                echo '<div class="campo-pedidos">';
                echo '<div class="inform-pedido">';
                echo '<h2>' . $nome . '</h2>';
                echo '<h3>' . htmlspecialchars($pedido['titulo_livro']) . '</h3>';
                echo '<h5>MATRÍCULA: ' . htmlspecialchars($pedido['matricula']) . '</h5>';
                echo '</div>';
                echo '<button class="btn-aceitar" data-id="' . htmlspecialchars($pedido['id']) . '" data-nome="' . $nome . '" data-titulo="' . htmlspecialchars($pedido['titulo_livro']) . '" data-matricula="' . htmlspecialchars($pedido['matricula']) . '"><i class="fa-solid fa-check"></i> Aceitar o Pedido</button>';
                echo '</div>';
            }

        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        ?>
    </div>

    <!-- The Modal -->
    <div id="container-modal" class="container-modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="container-text">
                <h3 id="modal-nome">Nome do Aluno</h3>
                <h3 id="modal-titulo">Título do Livro</h3>
                <h5 id="modal-matricula">MATRÍCULA</h5>
            </div>
            <div class="number-bibliot">
                <div class="number">
                    <h4>NÚMERO DE REGISTRO</h4>
                    <input type="text" id="modal-registro" placeholder="digite aqui...">
                </div>
                <div class="bibliot">
                    <h4>BIBLIOTECÁRIO</h4>
                    <select name="bibliotecario" id="modal-bibliotecario">
                        <option value="Bibliotecario">bibliotecarios</option>
                        <!-- Opcional: Preencher com opções reais -->
                    </select>
                </div>
                <button class="btn-confirmar" id="btn-confirma"><i class="fa-solid fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Formulário oculto para enviar dados via POST -->
    <form id="form-aceitar" action="funcoes/aceitar.php" method="POST" style="display:none;">
        <input type="hidden" name="matricula" id="form-matricula">
        <input type="hidden" name="numero_registro" id="form-registro">
    </form>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('container-modal');
        const closeModal = document.querySelector('.modal-content .close');
        const confirmButton = document.getElementById('btn-confirma');
        const form = document.getElementById('form-aceitar');
        const matriculaInput = document.getElementById('form-matricula');
        const registroInput = document.getElementById('form-registro');

        // Função para abrir o modal e preencher os dados
        function openModal(id, nome, titulo, matricula) {
            document.getElementById('modal-nome').textContent = nome;
            document.getElementById('modal-titulo').textContent = titulo;
            document.getElementById('modal-matricula').textContent = `MATRÍCULA: ${matricula}`;
            modal.style.display = 'block';

            // Atualiza os campos do formulário com os dados
            confirmButton.onclick = () => {
                matriculaInput.value = matricula;
                registroInput.value = document.getElementById('modal-registro').value; // Número de registro do input
                form.submit();
            };
        }

        // Adiciona event listeners aos botões de aceitar
        document.querySelectorAll('.btn-aceitar').forEach(button => {
            button.addEventListener('click', (event) => {
                const id = event.currentTarget.getAttribute('data-id');
                const nome = event.currentTarget.getAttribute('data-nome');
                const titulo = event.currentTarget.getAttribute('data-titulo');
                const matricula = event.currentTarget.getAttribute('data-matricula');
                
                openModal(id, nome, titulo, matricula);
            });
        });

        // Fecha o modal ao clicar no botão de fechar
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Fecha o modal ao clicar fora do conteúdo do modal
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    </script>
    <script src="scripts.js"></script>
</body>
</html>
