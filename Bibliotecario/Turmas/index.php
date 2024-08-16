<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="Css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-content form {
            display: flex;
            flex-direction: column;
        }

        .modal-content label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .form-row>div {
            flex: 1;
            margin-right: 10px;
        }

        .form-row>div:last-child {
            margin-right: 0;
        }

        .modal-content select,
        .modal-content input[type="number"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        .submit-button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        /* Estilo para os selects de filtragem */
        .filter-container select {
            padding: 10px;
            margin: 0 10px 10px 0;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }

        .filter-container select:hover {
            transform: scale(1.01);
        }

        /* Estilo para os botões */
        .filter-container .submit-button,
        .pagination .submit-button {
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-container .nextPage,
        .pagination .nextPage {
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-container .clear-button,
        .pagination .clear-button {
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            background-color: red;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-container button:focus,
        .pagination button:focus {
            outline: none;
            box-shadow: 0 0 10px #45a049;
        }

        .anoIniciofiltro {
            padding: 10px;
            margin: 0 10px 10px 0;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }
    </style>

</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="Logo Menu" class="logoMenu">
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
                <span style="font-size:43px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="Logo EEEP" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="Logo Biblioteca" class="library" />
                </div>
            </section>
        </nav>
    </header>

    <div class="container">
        <div class="bloco-top">
            <h1 class="title">TURMAS</h1>
            <button class="print-button" onclick="openAddModal()">ADICIONAR</button>
        </div>
        <div class="filter-container">
            <form id="filterForm" onsubmit="applyFilters(event)">
                <label for="filter_identificacao">Nome de Identificação:</label>
                <select id="filter_identificacao" name="filter_identificacao">
                    <option value="">Todos</option>
                    <option value="1º A - Enfermagem">1º A - Enfermagem</option>
                    <option value="1º B - Informática">1º B - Informática</option>
                    <option value="1º C - Comércio">1º C - Comércio</option>
                    <option value="1º D - Administração">1º D - Administração</option>
                    <option value="2º A - Enfermagem">2º A - Enfermagem</option>
                    <option value="2º B - Informática">2º B - Informática</option>
                    <option value="2º C - Comércio">2º C - Comércio</option>
                    <option value="2º D - Administração">2º D - Administração</option>
                    <option value="3º A - Enfermagem">3º A - Enfermagem</option>
                    <option value="3º B - Informática">3º B - Informática</option>
                    <option value="3º C - Comércio">3º C - Comércio</option>
                    <option value="3º D - Administração">3º D - Administração</option>
                </select>

                <label for="filter_curso">Curso:</label>
                <select id="filter_curso" name="filter_curso">
                    <option value="">Todos</option>
                    <option value="Enfermagem">Enfermagem</option>
                    <option value="Informática">Informática</option>
                    <option value="Comércio">Comércio</option>
                    <option value="Administração">Administração</option>
                </select>

                <label for="filter_ano">Ano de Início:</label>
                <input class="anoIniciofiltro" type="number" id="filter_ano" name="filter_ano" maxlength="4"
                    placeholder="Ex: 2024">

                <label for="filter_situacao">Situação:</label>
                <select id="filter_situacao" name="filter_situacao">
                    <option value="">Todos</option>
                    <option value="Ativo">Ativo</option>
                    <option value="Desativado">Desativado</option>
                </select>

                <button type="submit" class="submit-button">Filtrar</button>
                <button type="button" class="clear-button" onclick="clearFilters()">Limpar Filtros</button>
            </form>
        </div>



        <table id="booksTable">
            <?php
            // Conectar ao banco de dados
            require_once '../../dependencies/config.php';

            // Capturar o ano atual
            $ano_atual = date('Y');

            try {
                // Atualizar a coluna 'atividade' com base no ano atual
                $update_query = $conn->prepare("
        UPDATE turma
        SET atividade = CASE
            WHEN :ano_atual > ano_conclusao THEN 0
            WHEN :ano_atual >= ano_inicio AND :ano_atual <= ano_conclusao THEN 1
            ELSE 0
        END
    ");
                $update_query->bindValue(':ano_atual', $ano_atual, PDO::PARAM_INT);
                if (!$update_query->execute()) {
                    throw new Exception("Erro ao atualizar a coluna 'atividade': " . implode(", ", $update_query->errorInfo()));
                }

                // Atualizar a coluna 'nome_identificacao' com base no ano atual
                $update_query = $conn->prepare("
        UPDATE turma
        SET nome_identificacao = CASE 
            WHEN :ano_atual < ano_inicio THEN CONCAT('1º ', SUBSTR(nome_identificacao, 4)) 
            WHEN :ano_atual > ano_conclusao THEN CONCAT('3º ', SUBSTR(nome_identificacao, 4)) 
            WHEN ano_inicio = :ano_atual THEN CONCAT('1º ', SUBSTR(nome_identificacao, 4)) 
            WHEN ano_inicio + 1 = :ano_atual THEN CONCAT('2º ', SUBSTR(nome_identificacao, 4)) 
            WHEN ano_conclusao = :ano_atual THEN CONCAT('3º ', SUBSTR(nome_identificacao, 4)) 
            ELSE nome_identificacao
        END
    ");
                $update_query->bindValue(':ano_atual', $ano_atual, PDO::PARAM_INT);
                if (!$update_query->execute()) {
                    throw new Exception("Erro ao atualizar a coluna 'nome_identificacao': " . implode(", ", $update_query->errorInfo()));
                }

                // Atualizar a coluna 'serie' com base no ano atual
                $update_query = $conn->prepare("
        UPDATE turma
        SET serie = CASE 
            WHEN :ano_atual < ano_inicio THEN '1ª série' 
            WHEN :ano_atual > ano_conclusao THEN '3ª série' 
            WHEN ano_inicio = :ano_atual THEN '1ª série' 
            WHEN ano_inicio + 1 = :ano_atual THEN '2ª série' 
            WHEN ano_conclusao = :ano_atual THEN '3ª série' 
            ELSE serie
        END
    ");
                $update_query->bindValue(':ano_atual', $ano_atual, PDO::PARAM_INT);
                if (!$update_query->execute()) {
                    throw new Exception("Erro ao atualizar a coluna 'serie': " . implode(", ", $update_query->errorInfo()));
                }

                // Consulta ao banco de dados para buscar as turmas
                $stmt = $conn->prepare("SELECT * FROM turma");
                if (!$stmt->execute()) {
                    throw new Exception("Erro ao consultar as turmas: " . implode(", ", $stmt->errorInfo()));
                }



                // Exibe os resultados na tabela
                echo "<table id='booksTable'>";
                echo "<thead>
        <tr>
            <th>IDENTIFICAÇÃO</th>
            <th>CURSO</th>
            <th>Ano de Início - Ano de Conclusão</th>
            <th>SITUAÇÃO</th>
            <th>VISUALIZAR</th>
            <th>EDITAR</th>
        </tr>
    </thead>";
                echo "<tbody>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $atividade = $row['atividade'] == 1 ? 'Ativo' : 'Inativo';
                    echo "<tr>
            <td>{$row['nome_identificacao']}</td>
            <td>{$row['curso']}</td>
            <td>{$row['ano_inicio']} - {$row['ano_conclusao']}</td>
            <td>{$atividade}</td>
            <td><a href='alunos/index.php?turma_id={$row['id']}' class='icon-button'><i class='fa-solid fa-eye' style='color: #ffffff;'></i></a></td>
            <td><button class='icon-button' onclick='openEditModal({$row['id']})'><i class='fa-solid fa-pen-to-square' style='color: #ffffff;'></i></button></td>
        </tr>";
                }

                echo "</tbody>";
                echo "</table>";

            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
            ?>


        </table>

        <div class="pagination">
            <button id="prevPage" onclick="prevPage()">Anterior</button>
            <span id="pageInfo"></span>
            <button id="nextPage" onclick="nextPage()">Próximo</button>
        </div>

    </div>

    <!-- Modal para Adicionar Turma -->
    <div id="addTurmaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addTurmaModal')">&times;</span>
            <h2>Adicionar Turma</h2>
            <form id="addTurmaForm" method="POST" action="adicionar_turma.php">
                <div class="form-row">
                    <div>
                        <label for="nome_identificacao">Nome de Identificação:</label>
                        <select id="nome_identificacao" name="nome_identificacao" required>
                            <option value="">Selecione</option>
                            <option value="1º A - Enfermagem">1º A - Enfermagem</option>
                            <option value="1º B - Informática">1º B - Informática</option>
                            <option value="1º C - Comércio">1º C - Comércio</option>
                            <option value="1º D - Administração">1º D - Administração</option>
                        </select>
                    </div>
                    <div>
                        <label for="curso">Curso:</label>
                        <select id="curso" name="curso" required>
                            <option value="Enfermagem">Enfermagem</option>
                            <option value="Informática">Informática</option>
                            <option value="Comércio">Comércio</option>
                            <option value="Administração">Administração</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="ano_inicio">Ano de Início:</label>
                        <input type="number" id="ano_inicio" name="ano_inicio" required>
                    </div>
                    <div>
                        <label for="ano_conclusao">Ano de Conclusão:</label>
                        <input type="number" id="ano_conclusao" name="ano_conclusao" readonly>
                    </div>
                    <div>
                        <label for="serie">Série:</label>
                        <input type="number" id="serie" name="serie" required readonly>
                    </div>
                </div>

                <button type="submit" class="submit-button">Adicionar Turma</button>
            </form>
        </div>
    </div>

    <!-- Modal para Editar Turma -->
    <div id="editTurmaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Editar Turma</h2>
            <form id="editTurmaForm" method="POST" action="editar_turma.php">
                <input type="hidden" id="edit_turma_id" name="turma_id">
                <div class="form-row">
                    <div>
                        <label for="edit_nome_identificacao">Nome de Identificação:</label>
                        <select id="edit_nome_identificacao" name="nome_identificacao" required>
                            <option value="">Selecione</option>
                            <option value="1º A - Enfermagem">1º A - Enfermagem</option>
                            <option value="1º B - Informática">1º B - Informática</option>
                            <option value="1º C - Comércio">1º C - Comércio</option>
                            <option value="1º D - Administração">1º D - Administração</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_curso">Curso:</label>
                        <select id="edit_curso" name="curso" required>
                            <option value="Enfermagem">Enfermagem</option>
                            <option value="Informática">Informática</option>
                            <option value="Comércio">Comércio</option>
                            <option value="Administração">Administração</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="edit_ano_inicio">Ano de Início:</label>
                        <input type="number" id="edit_ano_inicio" name="ano_inicio" required readonly>
                    </div>
                    <div>
                        <label for="edit_ano_conclusao">Ano de Conclusão:</label>
                        <input type="number" id="edit_ano_conclusao" name="ano_conclusao" readonly>
                    </div>
                    <div>
                        <label for="edit_serie">Série:</label>
                        <input type="number" id="edit_serie" name="serie" required readonly>
                    </div>
                </div>


                <button type="submit" class="submit-button">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <script>
        // Abrir modal de adicionar turma
        function openAddModal() {
            document.getElementById('addTurmaModal').style.display = 'block';
            var currentYear = new Date().getFullYear(); // Obter o ano atual
            document.getElementById('ano_inicio').value = currentYear; // Definir o ano atual
            document.getElementById('ano_conclusao').value = currentYear + 2; // Definir o ano de conclusão automaticamente
            document.getElementById('serie').value = 0 + 1; // Definir a série como "1º"
            document.getElementById('addTurmaModal').style.display = 'block';
        }

        // Fechar modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Abrir modal de edição de turma
        function openEditModal(id) {
            $.ajax({
                url: 'get_turma.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function (turma) {
                    // Verifique o console para garantir que os dados estejam chegando corretamente
                    // Certifique-se de que os valores estejam corretos
                    if (turma) {
                        document.getElementById('edit_turma_id').value = turma.id;
                        document.getElementById('edit_nome_identificacao').value = turma.nome_identificacao;
                        document.getElementById('edit_curso').value = turma.curso;
                        document.getElementById('edit_ano_inicio').value = turma.ano_inicio;
                        document.getElementById('edit_ano_conclusao').value = turma.ano_conclusao;
                        document.getElementById('edit_serie').value = turma.serie;

                        // Exibir o modal de edição
                        document.getElementById('editTurmaModal').style.display = 'block';
                    } else {
                        alert('Erro ao carregar os dados da turma.');
                    }
                },
                error: function () {
                    alert('Erro ao carregar os dados da turma.');
                }
            });
        }


        // Fechar modal de edição
        function closeEditModal() {
            document.getElementById('editTurmaModal').style.display = 'none';
        }

        // Atualizar automaticamente o ano de conclusão e o curso no modal de adicionar turma
        document.getElementById('ano_inicio').addEventListener('input', function () {
            var anoInicio = parseInt(this.value);
            if (!isNaN(anoInicio)) {
                document.getElementById('ano_conclusao').value = anoInicio + 2;
            } else {
                document.getElementById('ano_conclusao').value = '';
            }
        });

        document.getElementById('nome_identificacao').addEventListener('change', function () {
            var curso = '';

            switch (this.value) {
                case '1º A - Enfermagem':
                case '2º A - Enfermagem':
                case '3º A - Enfermagem':
                    curso = 'Enfermagem';
                    break;
                case '1º B - Informática':
                case '2º B - Informática':
                case '3º B - Informática':
                    curso = 'Informática';
                    break;
                case '1º C - Comércio':
                case '2º C - Comércio':
                case '3º C - Comércio':
                    curso = 'Comércio';
                    break;
                case '1º D - Administração':
                case '2º D - Administração':
                case '3º D - Administração':
                    curso = 'Administração';
                    break;
            }

            document.getElementById('curso').value = curso;
        });

        // Atualizar automaticamente o ano de conclusão e o curso no modal de edição de turma
        document.getElementById('edit_ano_inicio').addEventListener('input', function () {
            var anoInicio = parseInt(this.value);
            if (!isNaN(anoInicio)) {
                document.getElementById('edit_ano_conclusao').value = anoInicio + 2;
            } else {
                document.getElementById('edit_ano_conclusao').value = '';
            }
        });

        document.getElementById('edit_nome_identificacao').addEventListener('change', function () {
            var curso = '';

            switch (this.value) {
                case '1º A - Enfermagem':
                case '2º A - Enfermagem':
                case '3º A - Enfermagem':
                    curso = 'Enfermagem';
                    break;
                case '1º B - Informática':
                case '2º B - Informática':
                case '3º B - Informática':
                    curso = 'Informática';
                    break;
                case '1º C - Comércio':
                case '2º C - Comércio':
                case '3º C - Comércio':
                    curso = 'Comércio';
                    break;
                case '1º D - Administração':
                case '2º D - Administração':
                case '3º D - Administração':
                    curso = 'Administração';
                    break;
            }

            document.getElementById('edit_curso').value = curso;
        });

        // Envio do formulário de adicionar turma via AJAX
        $(document).ready(function () {
            $('#addTurmaForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'adicionar_turma.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Erro na comunicação com o servidor.');
                    }
                });
            });

            // Envio do formulário de editar turma via AJAX
            $('#editTurmaForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'editar_turma.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
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

        function applyFilters(event) {
            event.preventDefault();

            var filterIdentificacao = document.getElementById('filter_identificacao').value.toLowerCase();
            var filterCurso = document.getElementById('filter_curso').value.toLowerCase();
            var filterAno = document.getElementById('filter_ano').value;
            var filterSituacao = document.getElementById('filter_situacao').value.toLowerCase();

            var rows = document.querySelectorAll('#booksTable tbody tr');

            rows.forEach(function (row) {
                var identificacao = row.cells[0].textContent.toLowerCase();
                var curso = row.cells[1].textContent.toLowerCase();
                var anoInicio = row.cells[2].textContent.split(' - ')[0];
                var situacao = row.cells[3].textContent.toLowerCase();

                var show = true;

                if (filterIdentificacao && !identificacao.includes(filterIdentificacao)) {
                    show = false;
                }
                if (filterCurso && !curso.includes(filterCurso)) {
                    show = false;
                }
                if (filterAno && anoInicio !== filterAno) {
                    show = false;
                }
                if (filterSituacao && situacao !== filterSituacao) {
                    show = false;
                }

                if (show) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

    </script>

    <script>
        function clearFilters() {
            document.getElementById('filterForm').reset();
            applyFilters(new Event('submit'));  // Reaplica os filtros após limpar
        }

        var currentPage = 1;
        var itemsPerPage = 8;  // Mostra 12 itens por página

        function showPage(page) {
            var rows = document.querySelectorAll('#booksTable tbody tr');
            var totalPages = Math.ceil(rows.length / itemsPerPage);

            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;

            for (var i = 0; i < rows.length; i++) {
                rows[i].style.display = 'none';
            }

            for (var i = (page - 1) * itemsPerPage; i < page * itemsPerPage && i < rows.length; i++) {
                rows[i].style.display = '';
            }

            document.getElementById('pageInfo').textContent = "Página " + page + " de " + totalPages;

            document.getElementById('prevPage').disabled = (page === 1);
            document.getElementById('nextPage').disabled = (page === totalPages);
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        }

        function nextPage() {
            var totalPages = Math.ceil(document.querySelectorAll('#booksTable tbody tr').length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            showPage(currentPage);
        });
    </script>


</body>

</html>