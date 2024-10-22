<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/styleEmprestimo.css">
    <link rel="stylesheet" href="Css/style.css">
    <title>Registro De Livros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script type="text/javascript" src="scripts.js"></script>
</head>

<body>

    <!-- nav bar -->
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
    <!-- nav-bar -->

    <div class="topbar" id="header"></div>
    <div class="Encapsulamento">
        <div class="grayBox">
            <div class="CadastrarLivro">
                <span>CADASTRAR EMPRÉSTIMO</span>
            </div>
            <form action="cadastrar.php" method="post" enctype="multipart/form-data">
                <div class="form-group full-width imgContainerImg">
                    <!-- Image upload preview will be here -->
                </div>
                
                    <div class="curso-turma">
        <div class="form-nome">
            <label for="aluno">Nome do Estudante:</label>
            <select id="aluno" name="aluno" required>
                <option value="">Selecione o aluno</option>
            </select>
        </div>
        <div class="form-row">
            <div class="form-cursos">
                <label for="curso">Curso:</label>
                <select id="curso" name="curso" required>
                    <option selected>Selecione:</option>
                    <option value='Professores'>Professor</option>
                    <option value="Enfermagem">Enfermagem</option>
                    <option value="Informática">Informática</option>
                    <option value="Administração">Administração</option>
                    <option value="Comércio">Comércio</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-turma">
                <label for="turma">Turma:</label>
                <select id="turma" name="turma" required>
                    <option selected>Selecione:</option>
                    <option value='4'>Professor</option>
                    <option value="1">1º</option>
                    <option value="2">2º</option>
                    <option value="3">3º</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group full-width">
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" placeholder="Digite a matrícula">
    </div>

    <div class="livro">
        <div class="form-group">
            <label for="titulolivro">Título do livro:</label>
            <input type="text" id="titulolivro" name="titulolivro" placeholder="Digite o título do livro" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
            <label for="registrationNumber">Número de Registro:</label>
            <input type="text" id="registrationNumber" name="registrationNumber" placeholder="Digite o número de registro">
        </div>
        <div class="form-group">
            <label for="nomebibliotecario">Nome Bibliotecário:</label>
            <input type="text" id="nomebibliotecario" name="nomebibliotecario" placeholder="Digite seu nome" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group full-width">
            <label for="observacao">Observação:</label>
            <input class="obs" type="text" id="observacao" name="observacao" placeholder="Digite aqui sua observação">
        </div>
    </div>

    <div class="button-container">
        <button type="submit" class="cadastrarButton">
            <i class="fas fa-check"></i> Adicionar
        </button>
    </div>
            </form>
        </div>
    </div>
    <script>
    
    document.addEventListener('DOMContentLoaded', function () {
    const cursoSelect = document.getElementById('curso');
    const turmaSelect = document.getElementById('turma');
    const alunoSelect = document.getElementById('aluno');
    const matriculaInput = document.getElementById('matricula');

    function fetchAlunos() {
        const curso = cursoSelect.value;
        const turma = turmaSelect.value;

        if (curso && turma) {
            fetch(`buscar_alunos.php?curso=${encodeURIComponent(curso)}&turma=${encodeURIComponent(turma)}`)
                .then(response => response.json())
                .then(alunos => {
                    alunoSelect.innerHTML = '<option value="">Selecione o aluno</option>'; // Limpa opções anteriores
                    alunos.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.id;
                        option.textContent = aluno.nome_completo;
                        option.dataset.matricula = aluno.matricula; // Armazena a matrícula no atributo data-matricula
                        alunoSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao buscar alunos:', error));
        }
    }

    function preencherDadosAluno() {
        const alunoOption = alunoSelect.options[alunoSelect.selectedIndex];

        if (alunoOption) {
            const matricula = alunoOption.dataset.matricula; // Obtém a matrícula do atributo data-matricula
            matriculaInput.value = matricula ? matricula : ''; // Preenche a matrícula automaticamente
        }
    }

    cursoSelect.addEventListener('change', fetchAlunos);
    turmaSelect.addEventListener('change', fetchAlunos);
    alunoSelect.addEventListener('change', preencherDadosAluno); // Preenche a matrícula quando o aluno é selecionado
});



</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Impede o envio padrão do formulário

                const formData = new FormData(form);

                fetch('cadastrar.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            alert(result.message);
                            window.location.reload(); // Recarrega a página após o sucesso
                        } else {
                            alert(result.message);
                        }
                    })
                    .catch(error => {
                        alert('Erro ao realizar o cadastro.');
                    });
            });
        });
    </script>

</body>

</html>
