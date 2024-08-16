<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/styleProfessor.css">
    <link rel="stylesheet" href="Css/style.css">
    <title>Cadastro Empréstimo Professor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript"  src="scripts.js"></script>
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
        
                    <a href=""><i class="fa-solid fa-file"></i>    Cadastrar Livro</a>
                    <a href=""><i class="fa-solid fa-book-open-reader"></i>    Cadastrar Empréstimo</a>
                    <a href=""><i class="fa-solid fa-book-bookmark"></i>    Banco de Livros</a>
                    <a href=""><i class="fa-brands fa-leanpub"></i>    Empréstimos</a>
                    <a href=""><i class="fa-solid fa-user-plus"></i>    Adicionar Turma</a>
                    <a href=""><i class="fa-solid fa-address-book"></i>    Pedidos</a>
                    <a href=""><i class="fa-solid fa-file-import"></i>    Relatório</a>
                    <a href="" class="sair"><i class="fa-solid fa-circle-xmark"></i>    Sair</a>
                </div>
                    
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer"onclick="abrirMenu()">&#9776;</span>
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
                <span>CADASTRAR EMPRÉSTIMO PROFESSOR</span>
            </div>
            <form action="register.php" method="post" enctype="multipart/form-data">
                <div class="form-group full-width imgContainerImg">
                    <!-- If you need an image container, add content here -->
                </div>

                <div class="curso-turma">   
                    <div class="form-row full-width">
                        <label for="Name">Nome do Professor:</label>
                        <input type="text" id="Name" name="Name" placeholder="Digite o nome do professor" required>
                    </div>
                </div>

                <div class="livro">
                    <div class="form-group">
                        <label for="titulolivro">Título do Livro:</label>
                        <input type="text" id="titulolivro" name="titulolivro" placeholder="Digite o título do livro" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="author">Autor do Livro:</label>
                        <input type="text" id="author" name="author" placeholder="Digite o nome do autor" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data">Escolha uma Data:</label>
                        <input type="date" id="data" name="data" placeholder="Escolha a data" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="registrationNumber">Número de Registro:</label>
                        <input type="text" id="registrationNumber" name="registrationNumber" placeholder="Digite o número de registro" required>
                    </div>  
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="observacao">Observação:</label>
                        <input class="obs" type="text" id="observacao" name="observacao" placeholder="Digite aqui sua observação" required>
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
        $(document).ready(function () {
            $('#header').load('../../Component/Menu_Nav');
        });
    </script>

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
