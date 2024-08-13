<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/your-path-to-uicons/css/uicons-[your-style].css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="./Css/index.css">
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()">&times;</button>
                </div>

                <div class="linha"></div>
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
                <span style="font-size:43px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library" />
                </div>
            </section>
        </nav>
    </header>

    <div class="container">
        <div class="bloco-top">
            <h1 class="title">TURMAS</h1>
            <button class="print-button">ADICIONAR</button>
        </div>
        <table id="booksTable">
            <thead>
                <tr>
                    <th>IDENTIFICAÇÃO</th>
                    <th>CURSO</th>
                    <th>Ano de Início - Ano de Conclusão</th>
                    <th>ATIVIDIDADE</th>
                    <th>VIZUALIZAR</th>
                    <th>EDITAR</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexão com o banco de dados
                require_once 'dependencies/config.php';

                // Consulta ao banco de dados
                $stmt = $conn->prepare("SELECT * FROM turma");
                $stmt->execute();

                // Exibe os resultados
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['nome_identificacao']}</td>
                            <td>{$row['curso']}</td>
                            <td></td>
                            <td>{$row['ano_inicio']} - {$row['ano_conclusao']}</td>
                            <td><button class='icon-button'><i class='fa-solid fa-eye' style='color: #ffffff;'></i></button></td>
                            <td><button class='icon-button'><i class='fa-solid fa-pen-to-square' style='color: #ffffff;'></i></button></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>
