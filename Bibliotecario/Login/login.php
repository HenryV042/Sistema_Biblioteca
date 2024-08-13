<php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Tela Login</title>
    <link rel="stylesheet" href="./style.css">
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
                  <span class="ClasseTexto">Acesso restrito</span>
                </div>
                <!-- campos de introodução de dados -->
                <form action="" method="post">
                  <!-- usuario -->
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label"></label>
                      <i class="fa-solid fa-user"></i>
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Usuario/Email">
                      <!-- fim usuario -->
                      
                      <!-- campo senha -->
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label"></label>
                      <i class="fa-solid fa-lock"></i>
                      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha">
                    </div>
                    <!-- fim campo senha -->

                    <button type="submit" class="Botao-entrar">Entrar</button>
                  </form>
                  <!-- fim campos de introodução de dados -->
            </div>
        </div>
      </main>
      <!-- fim do corpo do site -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
</php>
