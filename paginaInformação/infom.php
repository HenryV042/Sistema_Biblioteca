<php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Tela informação</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
  <!-- nav-bar -->
  <header>

<nav>
    <aside id="menu-Oculto" class="menu-Oculto">
        <div class="imagemMenu">
            <img src="img/logoMenu.png" alt="" class="logoMenu">
            <button class="fechar" href="" onclick="fecharMenu()">&times;</button>
                
        </div>
            
        <div class="linha-menu"></div>
        <div class="opcoes">

            <a href="">Cadastrar Livro</a>
            <a href="">Cadastrar Empréstimo</a>
            <a href="">Banco de Livros</a>
            <a href="">Empréstimos</a>
            <a href="">Adicionar Turma</a>
            <a href="">Pedidos</a>
            <a href="">Relatório</a>
            <a href="" class="sair">Sair</a>
        </div>
            
    </aside>
    <section id="principal">
        <span style="font-size:43px;cursor:pointer"onclick="abrirMenu()">&#9776;</span>
        <div class="nav-logo">
            <img  src="img/logoEEEP.png" alt="logo" class="logo_eeep"/>
            <div class="ret"></div>
            <img src="img/logoNav.png" alt="logo" class="library"/>
        </div>

    </section>
</nav>

</header>

    <!-- fim nav--bar -->

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
                <h3 class="">Nome:<span></span></h3>
                <h3 class="">matricula:<span></span></h3>
                <h3 class="">turma:<span></span></h3>
            
                </div>
                
                
            </div>

            <div class="informacao-emprestimo">
                <h1 class="titulo">INFORMAÇÃO DO EMPRESTIMO</h1>
                <div class="linha"></div>
                <div class="rot">
                <h3 class="">Nome:<span></span></h3>
                <h3 class="">matricula:<span></span></h3>
                <h3 class="">turma:<span></span></h3>
                <h3 class="">Nome:<span></span></h3>
                <h3 class="">matricula:<span></span></h3>
                <h3 class="">turma:<span></span></h3>
                </div>
                
            </div>
            
        </div>
      </main>
      <!-- fim do corpo do site -->
</body>
<script type="text/javascript"  src="script.js"></script>
</html>
</php>
