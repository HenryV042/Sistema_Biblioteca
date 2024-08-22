<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        
@import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Open Sans", sans-serif;
}
body{
   background-image: url(../img/ondas-governo-rodape.png);
   background-repeat: no-repeat;
   background-position: bottom;
   background-size: contain;
   height: 100%;
   background-attachment: fixed;
   
}

/* Header or Navbar */
header {
    background-image: linear-gradient(90deg, #26A737, #2DB39E);
    border-bottom: 3px solid #E84F0D;
    height: 8%;
    width: 100%;
}

                                    /* menu lateral */
.menu-Oculto{
   height: 100%;
   width: 0vw;
   position: fixed;
   z-index: 1;
   top: 0;
   left: 0;
   overflow-x: hidden;
   background-color: #ffffff;
   box-shadow: rgba(3, 3, 3, 0.521) 0.2px 0px 12px 0px ;
   transition: 0.5s;
}

.opcoes {
   display: flex;
   flex-direction: column;
   
}

.menu-Oculto a{
   text-decoration: none;
   color: rgb(34,166,56);
   padding: 24px 8px 8px 32px;
   font-size: 23px;
   display: block;
   transition: 0.4s;
}
.menu-Oculto a:hover{
   color: #ffffff;
   background-color: #89db86;
   
}
.fechar{
   position: absolute;
   top: 0;
   right: 25px;
   font-size: 50px;
   margin-left: 50px;
   border: none;
   background-color: white;
   cursor: pointer;
}

.logoMenu{
   height: 27vh;
   margin-left: 40px;
}
.imageMenu{
   display: flex;
   align-items: center;
}

.linha{
   width: 100%;
   height: 5px;
   background: linear-gradient(to right, #e84f0d 0%, #fccf00 100%);
   margin-bottom: 40px;
}
.opcoes .sair{
   color: red;
}
.opcoes .sair:hover{
   color: #ffffff;
   background-color: #ff0000;
}

#principal{
   display: flex;
   padding: 10px;
   transition: margin-left 0.5s;
}

#principal span{
   margin: 1px 10px 1px 20px;
   color: #ffffff;
   font-size: 4vh;
   cursor: pointer;

}

/* NavBar */
.nav-logo {
   display: flex;
}

.logo_eeep{
   margin: 1px 30px 1px 27px;

   height: 6vh;    
}

.ret{
   border-radius: 100px;
   width: 0.3vw;
   height: 5.5vh;
   background: #ffffff;
   margin-right: 12px;
}
.nav-logo .library{
   height: 5.8vh;
   margin: 0px 0px 0px 40px;
}

/* RESPONSIVIDADE MIDIA QUERRY */
@media (max-width: 560px) {
   .logo_eeep{
      margin: 0px 0px 0px 0px;
      height: 6vh;
   }
   .nav-logo .library{
      margin: 0px 0px 0px 0px;
      height: 5.6vh;
   }
   #principal span{
      margin: 0px 0px 0px 0px;
   }
   
}
@media (max-width: 390px) {
   .logo_eeep{

      height: 4.5vh;
   }
   .nav-logo .library{

      height: 4vh;
   }

}
@media (max-width: 390px) {
   .logoMenu{
      height: 22vh;
      margin-left: 20px;
   }
   .fechar{
      margin-left: 0;
      right: auto;
   }
   
   
}
    </style>
</head>

<body>
    <?php
    session_start(); // Iniciar sessão
    $message = $_SESSION['message'] ?? ''; // Obtém a mensagem da sessão
    unset($_SESSION['message']); // Limpa a mensagem após exibi-la
    ?>

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

    <!-- PARTE DA CONFIRMAÇÃO -->
    <div class="container-pedidos">
        <div class="titulo-pedidos">PEDIDOS</div>
        <?php
        require_once '../../dependencies/config.php'; // Incluindo o arquivo de conexão
        
        try {
            $sql = 'SELECT p.*, a.nome_completo, LEFT(a.sala_identificacao, 4) AS sala_digitos
                    FROM pedidos p
                    LEFT JOIN aluno a ON p.matricula = a.matricula';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($pedidos)) {
                echo '<div class="sem-pedidos">Sem pedidos</div>';
            } else {
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
                    <input type="text" id="modal-registro" placeholder="Digite aqui...">
                </div>
                <div class="number" style="margin-left: 30px;">
                    <h4>BIBLIOTECÁRIO</h4>
                    <input type="text" id="modal-bibliotecario" placeholder="Digite aqui...">
                </div>
                <button class="btn-confirmar" id="btn-confirma"><i class="fa-solid fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Formulário oculto para enviar dados via POST -->
    <form id="form-aceitar" action="funcoes/aceitar.php" method="POST" style="display:none;">
        <input type="hidden" name="matricula" id="form-matricula">
        <input type="hidden" name="numero_registro" id="form-registro">
        <input type="hidden" name="bibliotecario" id="form-bibliotecario">
    </form>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('container-modal');
            const closeModal = document.querySelector('.modal-content .close');
            const confirmButton = document.getElementById('btn-confirma');
            const form = document.getElementById('form-aceitar');
            const matriculaInput = document.getElementById('form-matricula');
            const registroInput = document.getElementById('form-registro');
            const bibliotecarioInput = document.getElementById('form-bibliotecario');

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
                    bibliotecarioInput.value = document.getElementById('modal-bibliotecario').value; // Nome do bibliotecário
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

            // Alerta e redirecionamento
            <?php if ($message): ?>
                alert("<?php echo addslashes($message); ?>");
                window.location.href = "javascript:history.back()"; // Redireciona para a página anterior
            <?php endif; ?>
        });
    </script>
    <script src="scripts.js"></script>
</body>

</html>
