<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/RegistrarLivro.css">
    <link rel="stylesheet" href="Css/style.css">
    <title>Registro De Livros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()"><i
                            class="fa-solid fa-circle-arrow-left"></i></button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <a href="../RegistrarLivro/"><i class="fa-solid fa-file"></i> Cadastrar Livro</a>
                    <a href="../cad-emprestimo/"><i class="fa-solid fa-book-open-reader"></i> Cadastrar Empréstimo</a>
                    <a href="../catalogos/"><i class="fa-solid fa-book-bookmark"></i> Banco de Livros</a>
                    <a href="../emprestimos/"><i class="fa-brands fa-leanpub"></i> Empréstimos</a>
                    <a href="../turmas/"><i class="fa-solid fa-user-plus"></i> Adicionar Turma</a>
                    <a href="../pedidos/"><i class="fa-solid fa-address-book"></i> Pedidos</a>
                    <a href="../ranking/"><i class="fa-solid fa-file-import"></i> Relatório</a>
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
    
    <div class="Encapsulamento">
        <div class="grayBox"">
            <div class="CadastrarLivro">
                <span>CADASTRAR LIVRO</span>
            </div>
            <form action="register.php" method="post" enctype="multipart/form-data">
                <div class="form-group full-width imgContainerImg">
                    <div class="file-input-container" id="fileInputContainer">
                        <input type="file" id="bookImage" name="bookImage" accept="image/*">
                        <img id="previewImage" src="" alt="Pré-visualização da imagem"
                            style="display: none; max-height: auto;">
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="bookName">Nome do Livro:</label>
                    <input type="text" id="bookName" name="bookName" placeholder="Digite o nome do livro" required>
                </div>

                <div class="form-group full-width">
                    <label for="author">Autor:</label>
                    <input type="text" id="author" name="author" placeholder="Digite o nome do autor" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cdu">CDU:</label>
                        <input type="text" id="cdu" name="cdu" placeholder="Digite o CDU" required>
                    </div>
                    <div class="form-group">
                        <label for="cdd">CDD:</label>
                        <input type="text" id="cdd" name="cdd" placeholder="Digite o CDD" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="origin">Origem:</label>
                        <input type="text" id="origin" name="origin" placeholder="Digite a origem" required>
                    </div>
                    <div class="form-group">
                        <label for="publisher">Editora:</label>
                        <input type="text" id="publisher" name="publisher" placeholder="Digite a editora" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Local:</label>
                        <input type="text" id="location" name="location" placeholder="Digite o local" required>
                    </div>
                    <div class="form-group">
                        <label for="genre">Gênero:</label>
                        <input type="text" id="genre" name="genre" placeholder="Digite o gênero" required>
                    </div>
                    <div class="form-group">
                        <label for="seal">Selo:</label>
                        <select id="seal" name="seal" required>
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="acquisitionYear">Ano de Aquisição:</label>
                        <input type="number" id="acquisitionYear" name="acquisitionYear"
                            placeholder="Digite o ano de aquisição" required>
                    </div>
                    <div class="form-group">
                        <label for="registrationNumber">Número de Registro:</label>
                        <input type="text" id="registrationNumber" name="registrationNumber"
                            placeholder="Digite o número de registro" required>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" class="cadastrarButton">
                        <i class="fas fa-check"></i> Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#header').load('../../Component/Menu_Nav');
        });
    </script>

    <script src="cdd_genero.js"></script>
    <script src="scripts.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Impede o envio padrão do formulário

                const formData = new FormData(form);

                fetch('register.php', {
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

            const fileInput = document.getElementById('bookImage');
            const fileInputContainer = document.getElementById('fileInputContainer');
            const previewImage = document.getElementById('previewImage');
            const fileIcon = document.querySelector('.file-icon');

            fileInputContainer.addEventListener('click', () => {
                fileInput.click();
            });

            fileInputContainer.addEventListener('dragover', (event) => {
                event.preventDefault();
                fileInputContainer.classList.add('dragover');
            });

            fileInputContainer.addEventListener('dragleave', () => {
                fileInputContainer.classList.remove('dragover');
            });

            fileInputContainer.addEventListener('drop', (event) => {
                event.preventDefault();
                fileInputContainer.classList.remove('dragover');
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    displayImage(files[0]);
                }
            });

            // Event listener to handle file selection
            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                    displayImage(file);
                }
            });

            // Function to display and resize the image
            function displayImage(file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function () {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        canvas.width = 256;
                        canvas.height = 256;

                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                        previewImage.src = canvas.toDataURL();
                        previewImage.style.display = 'block';
                        fileIcon.style.display = 'none';
                    };
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>