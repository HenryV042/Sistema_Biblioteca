<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="/sistema_biblioteca/dependencies/Menu_Nav/Css/style.css"><!-- Certifique-se de que este caminho está correto -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="/sistema_biblioteca/dependencies/Menu_Nav/img/logoMenu.png" alt="Logo Menu" class="logoMenu"> <!-- Verifique o caminho da imagem -->
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
                    <img src="/sistema_biblioteca/dependencies/Menu_Nav/img/logoEEEP.png" alt="Logo EEEP" class="logo_eeep">
                    <!-- Verifique o caminho da imagem -->
                    <div class="ret"></div>
                    <img src="/sistema_biblioteca/dependencies/Menu_Nav/img/logoNav.png" alt="Logo Library" class="library">
                    <!-- Verifique o caminho da imagem -->
                </div>
            </section>
        </nav>
    </header>
    <script type="text/javascript" src="/sistema_biblioteca/dependencies/Menu_Nav/scripts.js"></script> <!-- Verifique o caminho do script -->
</body>

</html>