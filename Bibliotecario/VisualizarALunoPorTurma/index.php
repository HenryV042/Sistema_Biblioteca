<?php
// index.php

require_once '../../dependencies/config.php'; // Ajuste o caminho conforme necessário

// Número de registros por página
$records_per_page = 12;

// Obter o número da página atual da URL, padrão para 1 se não definido
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1); // Garantir que a página seja no mínimo 1
$offset = ($page - 1) * $records_per_page;

// Consulta para obter o número total de registros
$total_query = $conn->query('SELECT COUNT(*) FROM aluno');
$total_records = $total_query->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Consulta para obter os registros da página atual
$sql = 'SELECT * FROM aluno LIMIT :limit OFFSET :offset';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter todas as turmas disponíveis com atividade = 1
$turmas_query = $conn->query('SELECT nome_identificacao, curso, serie FROM turma WHERE atividade = 1');
$turmas = $turmas_query->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="" id="header"></div>

    <div class="Hiddenbox">
        <div class="container">
            <div class="c2">
                <button class="print-button">Adicionar Aluno</button>
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
                            <td><?php echo htmlspecialchars($aluno['numero']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['matricula']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['cpf']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome_completo']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['curso']) ?></td>
                            <td><?php echo htmlspecialchars($aluno['serie']) ?></td>
                            <td>
                                <button class="icon-button">
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
                        <select id="sala_identificacao" name="sala_identificacao" required>
                            <option value="">Selecione uma Sala</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo htmlspecialchars($turma['nome_identificacao']) ?>">
                                    <?php echo htmlspecialchars($turma['nome_identificacao']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>

                        <label for="curso">Curso:</label>
                        <input type="text" id="curso" name="curso" required readonly>

                        <label for="serie">Série:</label>
                        <input type="text" id="serie" name="serie" required readonly>

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
                        <select id="edit_sala_identificacao" name="sala_identificacao" required>
                            <option value="">Selecione uma Sala</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo htmlspecialchars($turma['nome_identificacao']) ?>">
                                    <?php echo htmlspecialchars($turma['nome_identificacao']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>

                        <label for="edit_curso">Curso:</label>
                        <input type="text" id="edit_curso" name="curso" required readonly>

                        <label for="edit_serie">Série:</label>
                        <input type="text" id="edit_serie" name="serie" required readonly>

                        <button type="submit">Salvar Alterações</button>
                    </form>
                </div>
            </div>


            <script>
                // Open the modal
                function openModal() {
                    document.getElementById('modal').style.display = 'block';
                }

                // Close the modal
                function closeModal() {
                    document.getElementById('modal').style.display = 'none';
                }

                // Add event listener to the 'Adicionar Aluno' button
                document.querySelector('.print-button').addEventListener('click', openModal);

                // Populate curso and serie fields based on selected sala_identificacao
                document.getElementById('sala_identificacao').addEventListener('change', function () {
                    const selectedValue = this.value;

                    <?php foreach ($turmas as $turma): ?>
                        if (selectedValue === "<?php echo htmlspecialchars($turma['nome_identificacao']); ?>") {
                            document.getElementById('curso').value = "<?php echo htmlspecialchars($turma['curso']); ?>";
                            document.getElementById('serie').value = "<?php echo htmlspecialchars($turma['serie']); ?>";
                        }
                    <?php endforeach ?>
                });

                // Abrir modal de edição e preencher com os dados do aluno
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

                // Fechar modal de edição
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

            <script>
                $(document).ready(function () {
                    $('#header').load('../../dependencies/Menu_Nav');
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


            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1 ?>">« Anterior</a>
                <?php endif ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1 ?>">Próximo »</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>

</html>