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

</head>

<style>
    /* Estilo para o modal */
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
</style>

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
            <button class="print-button" onclick="openModal()">ADICIONAR</button>
        </div>
        <table id="booksTable">

            <?php
            // Conectar ao banco de dados
            require_once '../../dependencies/config.php';

            // Capturar o ano atual
            $ano_atual = date('Y');

            // Atualizar a coluna 'atividade' para 0 se o ano de conclusão for menor que o ano atual
            $update_query = $conn->prepare("UPDATE turma SET atividade = 0 WHERE ano_conclusao < :ano_atual");
            $update_query->bindValue(':ano_atual', $ano_atual, PDO::PARAM_INT);
            $update_query->execute();

            // Consulta ao banco de dados para buscar as turmas
            $stmt = $conn->prepare("SELECT * FROM turma");
            $stmt->execute();

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
                $atividade = $row['atividade'] == 1 ? 'Ativo' : 'Desativado';
                echo "<tr>
            <td>{$row['nome_identificacao']}</td>
            <td>{$row['curso']}</td>
            <td>{$row['ano_inicio']} - {$row['ano_conclusao']}</td>
            <td>{$atividade}</td>
            <td><a href='alunos/index.php?turma_id={$row['id']}' class='icon-button'><i class='fa-solid fa-eye' style='color: #ffffff;'></i></a></td>
            <td><button class='icon-button'><i class='fa-solid fa-pen-to-square' style='color: #ffffff;'></i></button></td>
          </tr>";
            }

            echo "</tbody>";
            echo "</table>";
            ?>

        </table>
    </div>

    <div id="addTurmaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
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
                            <option value="2º A - Enfermagem">2º A - Enfermagem</option>
                            <option value="2º B - Informática">2º B - Informática</option>
                            <option value="2º C - Comércio">2º C - Comércio</option>
                            <option value="2º D - Administração">2º D - Administração</option>
                            <option value="3º A - Enfermagem">3º A - Enfermagem</option>
                            <option value="3º B - Informática">3º B - Informática</option>
                            <option value="3º C - Comércio">3º C - Comércio</option>
                            <option value="3º D - Administração">3º D - Administração</option>
                        </select>
                    </div>
                    <div>
                        <label for="curso">Curso:</label>
                        <select id="curso" name="curso" required>
                            <option value="">Selecione</option>
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
                        <select id="serie" name="serie" required>
                            <option value="1º">1º</option>
                            <option value="2º">2º</option>
                            <option value="3º">3º</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="submit-button">Adicionar</button>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        // Abrir o modal
        function openModal() {
            document.getElementById('addTurmaModal').style.display = 'block';
        }

        // Fechar o modal
        function closeModal() {
            document.getElementById('addTurmaModal').style.display = 'none';
        }

        // Calcular o ano de conclusão automaticamente
        document.getElementById('ano_inicio').addEventListener('input', function () {
            var anoInicio = parseInt(this.value);
            if (!isNaN(anoInicio)) {
                document.getElementById('ano_conclusao').value = anoInicio + 2;
            } else {
                document.getElementById('ano_conclusao').value = '';
            }
        });

        // Preencher o curso automaticamente com base na seleção de Nome de Identificação
        document.getElementById('nome_identificacao').addEventListener('change', function () {
            var curso = '';

            switch (this.value) {
                case '1º A':
                case '2º A':
                case '3º A':
                    curso = 'Enfermagem';
                    break;
                case '1º B':
                case '2º B':
                case '3º B':
                    curso = 'Informática';
                    break;
                case '1º C':
                case '2º C':
                case '3º C':
                    curso = 'Comércio';
                    break;
                case '1º D':
                case '2º D':
                case '3º D':
                    curso = 'Administração';
                    break;
            }

            document.getElementById('curso').value = curso;
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Capturar o formulário e enviar os dados via AJAX
            $('#addTurmaForm').on('submit', function (e) {
                e.preventDefault(); // Prevenir o envio padrão do formulário

                $.ajax({
                    url: 'adicionar_turma.php', // O script PHP que processa a inserção
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

    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>