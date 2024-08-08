<php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login aluno</title>
    <link rel="stylesheet" href="./aluno.css">
</head>
<body>
  <!-- nav-bar -->
    <header>
        <nav>

        </nav>
    </header>
    <!-- fim nav--bar -->

    <!-- corpo do site -->
    <main>
        <div class="conteiner">
            <div class="area-login">
                <img src="./img/img-logo.png" class="Imagelogo" alt="1">
                <div class="restrito">
                  <span class="ClasseTexto">Aluno</span>
                </div>
                <!-- campos de introdução de dados -->
                 <div class="coleta-dados">
                <form action="" method="post">
                  <label for="cpf">CPF:</label>
                   <input class="cpf" type="text" id="cpf" name="cpf" pattern="[0-9]{11}" required>

                   <label for="matricula">Matrícula:</label>
                    <input type="text" id="matricula" name="matricula" required>

                <button type="submit" class="Botao-entrar">Entrar</button>

                  <h4>
                  Não sabe sua matrícula?  
                  Dirija-se à secretaria ou biblioteca e solicite ajuda ou acesse o <a href="#">aluno online</a>
                  </h4>
                </form>
                </div>
            </div>
        </div>
      </main>
      <!-- fim do corpo do site -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
</php>
