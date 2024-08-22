<?php
require_once '../../../dependencies/config.php'; // Ajuste o caminho conforme necessário

// Número de registros por página
$records_per_page = 12;

// Obter o número da página atual da URL, padrão para 1 se não definido
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1); // Garantir que a página seja no mínimo 1
$offset = ($page - 1) * $records_per_page;

// Obter o ID da turma da URL
$turma_id = isset($_GET['turma_id']) ? (int) $_GET['turma_id'] : 0;

// Consulta para obter o número total de registros na turma específica
$total_query = $conn->prepare('SELECT COUNT(*) FROM aluno WHERE id_turma = :turma_id');
$total_query->bindValue(':turma_id', $turma_id, PDO::PARAM_INT);
$total_query->execute();
$total_records = $total_query->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Consulta para obter os registros da página atual na turma específica
$sql = 'SELECT * FROM aluno WHERE id_turma = :turma_id LIMIT :limit OFFSET :offset';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':turma_id', $turma_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter detalhes da turma
$turma_query = $conn->prepare('SELECT * FROM turma WHERE id = :id');
$turma_query->bindValue(':id', $turma_id, PDO::PARAM_INT);
$turma_query->execute();
$turma = $turma_query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Alunos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../Css/index.css">
    <link rel="stylesheet" href="../Css/style.css">
    <style>
        /* Estilos para a navegação de paginação */
.pagination {
    text-align: center;
    margin: 20px 0;
}

.pagination a {
    margin: 0 5px;
    padding: 8px 16px;
    text-decoration: none;
    color: #007bff;
    border: 1px solid #007bff;
    border-radius: 4px;
    display: inline-block;
}

.pagination a.active {
    background-color: #007bff;
    color: white;
}

.pagination a:hover {
    background-color: #0056b3;
    color: white;
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
        
                    <a href="../../registrarlivro"><i class="fa-solid fa-file"></i>    Cadastrar Livro</a>
                    <a href="../../cad-emprestimo"><i class="fa-solid fa-book-open-reader"></i>    Cadastrar Empréstimo</a>
                    <a href="../../catalogo-bibliotecario"><i class="fa-solid fa-book-bookmark"></i>    Banco de Livros</a>
                    <a href="../../emprestimos"><i class="fa-brands fa-leanpub"></i>    Empréstimos</a>
                    <a href="../../turmas"><i class="fa-solid fa-user-plus"></i>    Adicionar Turma</a>
                    <a href="../../pedidos"><i class="fa-solid fa-address-book"></i>    Pedidos</a>
                    <a href="../../ranking"><i class="fa-solid fa-medal"></i>    Ranking</a>
                    <a href="../../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i>    Sair</a>
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

    <div class="Hiddenbox">
        <div class="container">
            <div class="btnsContainer">
                <a class="voltar" href="../">
                    <i class="fa-solid fa-circle-arrow-left" style="color: #26a737;"></i>
                </a>

                <div class="c2">
                    <button class="print-button">Adicionar Aluno</button>
                </div>
            </div>

            <h1 class="title">ALUNOS</h1>
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
                            <td><?php echo htmlspecialchars($aluno['numero']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['matricula']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['cpf']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome_completo']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['curso']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['serie']) ?></td>
                            <td>
                                <button class="icon-button" onclick='openEditModal(<?php echo json_encode($aluno); ?>)'>
                                    <i class="fa-solid fa-pen" style="color: #ffffff;"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div id="modal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeModal()">&times;</span>
                    <h2>Adicionar Aluno</h2>
                    <form id="addStudentForm" method="post" action="addAluno.php">
                        <label for="nome">Nome do Estudante:</label>
                        <input type="text" id="nome" name="nome" required>

                        <label for="numero">Numero da Chamada:</label>
                        <input type="text" id="numero" name="numero" maxlength="2" required>

                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" maxlength="14" name="cpf" required>

                        <label for="matricula">Matrícula:</label>
                        <input type="text" id="matricula" name="matricula" required>

                        <label for="sala_identificacao">Sala Identificação:</label>
                        <input type="text" id="sala_identificacao" name="sala_identificacao"
                            value="<?php echo htmlspecialchars($turma['nome_identificacao']); ?>" readonly>

                        <label for="curso">Curso:</label>
                        <input type="text" id="curso" name="curso"
                            value="<?php echo htmlspecialchars($turma['curso']); ?>" readonly>

                        <label for="serie">Série:</label>
                        <input type="text" id="serie" name="serie"
                            value="<?php echo htmlspecialchars($turma['serie']); ?>" readonly>

                        <button type="submit">Adicionar</button>
                    </form>
                    <div id="message" style="margin-top: 10px;"></div>
                </div>
            </div>

            <!-- Modal para Editar Aluno -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeEditModal()">&times;</span>
                    <h2>Editar Aluno</h2>
                    <form id="editStudentForm" method="post" action="editAluno.php">
                        <input type="hidden" id="edit_id" name="id">

                        <label for="edit_nome">Nome do Estudante:</label>
                        <input type="text" id="edit_nome" name="nome" required>

                        <label for="edit_numero">Numero da Chamada:</label>
                        <input type="text" id="edit_numero" maxlength="2" name="numero" required>

                        <label for="edit_cpf">CPF:</label>
                        <input type="text" id="edit_cpf" maxlength="14" name="cpf" required>

                        <label for="edit_matricula">Matrícula:</label>
                        <input type="text" id="edit_matricula" maxlength="30" name="matricula" required>

                        <label for="edit_sala_identificacao">Sala Identificação:</label>
                        <input type="text" id="edit_sala_identificacao" name="sala_identificacao"
                            value="<?php echo htmlspecialchars($turma['nome_identificacao']); ?>" readonly>

                        <label for="edit_curso">Curso:</label>
                        <input type="text" id="edit_curso" name="curso"
                            value="<?php echo htmlspecialchars($turma['curso']); ?>" readonly>

                        <label for="edit_serie">Série:</label>
                        <input type="text" id="edit_serie" name="serie"
                            value="<?php echo htmlspecialchars($turma['serie']); ?>" readonly>

                        <button type="submit">Salvar Alterações</button>
                    </form>
                </div>
            </div>

            <!-- Navegação de paginação -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&turma_id=<?php echo $turma_id; ?>">Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&turma_id=<?php echo $turma_id; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&turma_id=<?php echo $turma_id; ?>">Próximo</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

        

    <script>
        // Função para abrir o modal de adição de aluno
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        // Função para fechar o modal de adição de aluno
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function goback() {
            window.location.href = '../index.php'
        }

        // Adicionar evento no botão 'Adicionar Aluno' para abrir o modal
        document.querySelector('.print-button').addEventListener('click', openModal);
        document.querySelector('.GoBackBTN').addEventListener('click', goback);

        // Função para abrir o modal de edição e preencher com os dados do aluno
        function openEditModal(aluno) {
            document.getElementById('edit_id').value = aluno.id;
            document.getElementById('edit_nome').value = aluno.nome_completo;
            document.getElementById('edit_numero').value = aluno.numero;
            document.getElementById('edit_cpf').value = aluno.cpf;
            document.getElementById('edit_matricula').value = aluno.matricula;
            document.getElementById('editModal').style.display = 'block';
        }

        // Função para fechar o modal de edição
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Adicionar máscaras aos campos de CPF e Matrícula
        $(document).ready(function () {
            $('#cpf').on('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                this.value = value;
            });

            $('#matricula').on('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 9) value = value.slice(0, 9);
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                this.value = value;
            });
        });

        $(document).ready(function () {
            $('#edit_cpf').on('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                this.value = value;
            });

            $('#edit_matricula').on('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 9) value = value.slice(0, 9);
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                this.value = value;
            });
        });

        $(document).ready(function () {
            // Capturar o formulário de edição e enviar os dados via AJAX
            $('#editStudentForm').on('submit', function (e) {
                e.preventDefault(); // Prevenir o envio padrão do formulário

                $.ajax({
                    url: 'editAluno.php', // O script PHP que processa a edição
                    type: 'POST',
                    data: $(this).serialize(), // Serializar todos os campos do formulário
                    dataType: 'json', // Esperar uma resposta JSON do servidor
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // Recarregar a página atual para refletir as mudanças
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Erro na comunicação com o servidor.');
                    }
                });
            });
        });

        $(document).ready(function () {
            // Capturar o formulário de adição e enviar os dados via AJAX
            $('#addStudentForm').on('submit', function (e) {
                e.preventDefault(); // Prevenir o envio padrão do formulário

                $.ajax({
                    url: 'addAluno.php', // O script PHP que processa a adição
                    type: 'POST',
                    data: $(this).serialize(), // Serializar todos os campos do formulário
                    dataType: 'json', // Esperar uma resposta JSON do servidor
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // Recarregar a página atual para refletir as mudanças
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Erro na comunicação com o servidor.');
                    }
                });
            });
        });


    </script>

    <script>
        $(document).ready(function () {
            $('#header').load('../../../dependencies/Menu_Nav');
        });
    </script>

    <script>
        // Função para aplicar máscara de CPF
        function applyCpfMask(input) {
            let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            value = value.replace(/^(\d{3})(\d)/, '$1.$2');
            value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
            input.value = value;
        }

        // Função para aplicar máscara de matrícula
        function applyMatriculaMask(input) {
            let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            value = value.replace(/^(\d{8})(\d)/, '$1-$2');
            input.value = value;
        }

        // Adicionar eventos de máscara nos campos de CPF e matrícula
        document.getElementById('cpf').addEventListener('input', function () {
            applyCpfMask(this);
        });

        document.getElementById('matricula').addEventListener('input', function () {
            applyMatriculaMask(this);
        });

        document.getElementById('edit_cpf').addEventListener('input', function () {
            applyCpfMask(this);
        });

        document.getElementById('edit_matricula').addEventListener('input', function () {
            applyMatriculaMask(this);
        });

        // Funções para abrir e fechar os modais permanecem inalteradas
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function openEditModal(aluno) {
            document.getElementById('edit_id').value = aluno.id;
            document.getElementById('edit_nome').value = aluno.nome_completo;
            document.getElementById('edit_numero').value = aluno.numero;
            document.getElementById('edit_cpf').value = aluno.cpf;
            document.getElementById('edit_matricula').value = aluno.matricula;
            document.getElementById('edit_sala_identificacao').value = aluno.sala_identificacao;
            document.getElementById('edit_curso').value = aluno.curso;
            document.getElementById('edit_serie').value = aluno.serie;

            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Adicionar evento nos botões de edição para abrir o modal com os dados do aluno
        document.querySelectorAll('.icon-button').forEach(function (button, index) {
            button.addEventListener('click', function () {
                var aluno = <?php echo json_encode($alunos); ?>[index];
                openEditModal(aluno);
            });
        });

        // Atualizar curso e série ao alterar sala_identificacao no modal de edição
        document.getElementById('edit_sala_identificacao').addEventListener('change', function () {
            const selectedValue = this.value;

            <?php foreach ($turmas as $turma): ?>
                if (selectedValue === "<?php echo htmlspecialchars($turma['nome_identificacao']); ?>") {
                    document.getElementById('edit_curso').value = "<?php echo htmlspecialchars($turma['curso']); ?>";
                    document.getElementById('edit_serie').value = "<?php echo htmlspecialchars($turma['serie']); ?>";
                }
            <?php endforeach ?>
        });
    </script>

<script src="scripts.js"></script>

</body>

</html>