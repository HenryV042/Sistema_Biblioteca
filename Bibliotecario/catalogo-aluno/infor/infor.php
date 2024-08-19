<?php
require_once '../../../../dependencies/config.php';

// Recuperar o título do livro da URL
$titulo = isset($_GET['titulo_livro']) ? $_GET['titulo_livro'] : '';

// Função para buscar a URL da capa do livro usando a Google Books API com cURL
function getBookCover($titulo)
{
    global $conn; // Usar a conexão global para consultar o banco de dados

    // Verifica se a imagem já está no banco de dados
    $stmt = $conn->prepare("SELECT imagem FROM livros WHERE titulo_livro = :titulo");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->execute();
    $imagemData = $stmt->fetchColumn();

    // Verifica e cria a pasta de cache se não existir
    $cacheDir = '../cache/';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    // Se já há uma imagem no banco de dados, cria um arquivo temporário para a imagem
    if ($imagemData) {
        $tempFile = $cacheDir . md5($titulo) . '.jpg';
        file_put_contents($tempFile, $imagemData);
        return $tempFile;
    }

    // Se não há imagem no banco de dados, busca na Google Books API
    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;
    $cacheFile = $cacheDir . md5($titulo) . '.jpg';

    // Verifica se a imagem já está em cache
    if (file_exists($cacheFile)) {
        return $cacheFile;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return '../img/sem-foto.png'; // Retorna uma imagem padrão em caso de erro
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        $imageUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        $imageData = file_get_contents($imageUrl);

        if ($imageData !== false) {
            file_put_contents($cacheFile, $imageData);
            // Atualiza o banco de dados com o caminho da imagem
            $stmt = $conn->prepare("UPDATE livros SET imagem = :imagem WHERE titulo_livro = :titulo");
            $stmt->bindParam(':imagem', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->execute();
            return $cacheFile;
        }
    }

    return '../img/sem-foto.png'; // Retorna uma imagem padrão se não encontrar a capa
}

// Buscar a capa do livro
$imagem = getBookCover($titulo);

try {
    // Conectar ao banco de dados usando PDO
    global $conn; // Acessar a conexão PDO do config.php

    // Preparar a consulta SQL para buscar as informações do livro
    $sql = "SELECT titulo_livro, autor, editora, ano_aquisicao, origem, local, genero 
            FROM livros 
            WHERE titulo_livro = :titulo 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->execute();

    // Buscar os resultados
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $titulo_livro = htmlspecialchars($row['titulo_livro']);
        $autor = htmlspecialchars($row['autor']);
        $editora = htmlspecialchars($row['editora']);
        $ano_aquisicao = htmlspecialchars($row['ano_aquisicao']);
        $origem = htmlspecialchars($row['origem']);
        $local = htmlspecialchars($row['local']);
        $genero = htmlspecialchars($row['genero']);
    } else {
        // Dados não encontrados
        $titulo_livro = $autor = $editora = $ano_aquisicao = $origem = $local = $genero = 'Não disponível';
    }

    // Preparar a consulta SQL para verificar o status do livro
    $sqlStatus = "SELECT COUNT(*) FROM emprestimos 
                  WHERE titulo_livro = :titulo AND data_devolucao IS NULL";

    $stmtStatus = $conn->prepare($sqlStatus);
    $stmtStatus->bindParam(':titulo', $titulo);
    $stmtStatus->execute();
    $livrosEmprestados = $stmtStatus->fetchColumn();

    // Definir o status do livro
    $status = $livrosEmprestados > 0 ? 'Indisponível' : 'Disponível';

} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Livro</title>
    <link rel="stylesheet" href="../Css/style-bibli.css">
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/infor.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="scripts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="scripts.js"></script>
    <style>
        .img-container img {
            width: auto;
            height: auto;
            max-width: 90%;
            max-height: 90vh;
            display: block;
            margin: 0 auto;
        }

        .main-infor {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="../img/logoMenu.png" alt="Logo Menu" class="logoMenu">
                    <button class="fechar" onclick="fecharMenu()">&times;</button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <a href="../../../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i> Sair</a>
                </div>
            </aside>
            <section id="principal">
                <span style="font-size:43px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="../img/logoEEEP.png" alt="Logo EEEP" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="../img/logoNav.png" alt="Logo Biblioteca" class="library" />
                </div>
            </section>
        </nav>
    </header>

    <div class="main-infor">
        <div class="main-container">
            <div class="img-container">
                <img src="<?php echo $imagem; ?>" alt="Capa do Livro">
            </div>
            <!-- Formulário -->
            <form action="enviar_pedidos.php" method="POST">
                <!-- Text -->
                <h1>MAIS INFORMAÇÕES</h1>

                <!-- Inputs em coluna: Título e Autor -->
                <div class="inputs-row">
                    <div class="inputs">
                        <label for="#">TÍTULO DO LIVRO</label>
                        <input type="text" readonly name="titulo_livro" value="<?php echo $titulo_livro; ?>">
                    </div>
                    <div class="inputs">
                        <label for="#">AUTOR</label>
                        <input type="text" readonly value="<?php echo $autor; ?>">
                    </div>
                </div>

                <!-- Inputs em linha: Status e Gênero -->
                <div class="inputs-inline">
                    <div class="inputs">
                        <label for="#">STATUS</label>
                        <input type="text" readonly value="<?php echo $status; ?>">
                    </div>
                    <div class="inputs">
                        <label for="#">GÊNERO</label>
                        <input type="text" readonly value="<?php echo $genero; ?>">
                    </div>
                </div>

                <!-- Botões em linha -->
                <div class="buttom-container">
                    <div class="buttom">
                        <?php 
                        if($status == 'Disponível'){
                            echo '<button type="submit" id="buttom-l">';
                            echo    'Fazer Pedido';
                            echo '</button>';
                        }
                        ?>
                    </div>

                    <div class="buttom">
                        <button type="button" id="buttom-voltar">
                            <a href="../" class="button-link">Voltar</a>
                        </button>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
    document.getElementById('buttom-voltar').addEventListener('click', function(event) {
        event.preventDefault(); // Impede o envio do formulário

        window.location.href = '../'; // Redireciona para a página desejada
    });


    </script>
    <script src="../scripts.js"></script>
</body>

</html>
