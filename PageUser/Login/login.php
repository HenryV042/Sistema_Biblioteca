<php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <title>Tela Login</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header>
        <nav>

        </nav>
    </header>
    <main>
        <div class="conteiner">
            <div class="area-login">
                <img src="./img/img-logo.png" class="Imagelogo" alt="1">
                <div class="restrito">
                  <span class="ClasseTexto">Acesso restrito</span>
                </div>
                <form action="" method="post">
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label"></label>
                      <img class="iconeUsuario" src="img/do-utilizador.png" alt="" >
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Usuario/Email">
                      
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label"></label>
                      <img class="iconeTranca" src="img/trancar.png" alt="">
                      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha">
                    </div>
                    <button type="submit" class="BotaoDeentrar">Entrar</button>
                  </form>
            </div>
        </div>
      </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
</php>
