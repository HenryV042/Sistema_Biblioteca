<?php
require_once '../../../../dependencies/config.php';

// Recuperar o título do livro da URL
$titulo = isset($_GET['titulo_livro']) ? $_GET['titulo_livro'] : '';

function getBookData($titulo)
{
    global $conn;

    // Buscar o total de livros com o mesmo título
    $stmt = $conn->prepare("SELECT COUNT(*) FROM livros WHERE titulo_livro = :titulo_livro");
    $stmt->bindParam(':titulo_livro', $titulo);
    $stmt->execute();
    $totalLivros = $stmt->fetchColumn();

    // Buscar a quantidade de livros emprestados com o mesmo título
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM emprestimos 
        WHERE titulo_livro = :titulo_livro AND data_devolucao IS NULL
    ");
    $stmt->bindParam(':titulo_livro', $titulo);
    $stmt->execute();
    $livrosEmprestados = $stmt->fetchColumn();

    $livrosDisponiveis = $totalLivros - $livrosEmprestados;

    return [$totalLivros, $livrosDisponiveis];
}

list($totalLivros, $livrosDisponiveis) = getBookData($titulo);



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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <!-- Menu content here -->
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep"/>
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library"/>
                </div>
            </section>
        </nav>
    </header>
    <div class="container">
        <div class="img-livro">
            <img src="<?php echo $imagem; ?>" alt="Capa do Livro">
        </div>
        <div class="formulario">
            <form method="POST" action="update_livro.php">
                <h2>MAIS INFORMAÇÕES</h2>
                <!-- inputs e selects -->
                <label for="titulo_livro">NOME DO LIVRO</label>
                <input type="text" id="titulo_livro" name="titulo_livro" placeholder="NOME DO LIVRO" value="<?php echo htmlspecialchars($titulo); ?>" required>

                <label for="autor">AUTOR</label>
                <input type="text" id="autor" name="autor" placeholder="AUTOR" required>

                <div class="input-group">
                    <label for="titulo_livro">NOME DO LIVRO</label>
                    <label for="titulo_livro">NOME DO LIVRO</label>
                </div>
                <div class="input-group">
                    <input type="text" id="cdu" name="cdu" placeholder="CDU">
                    <input type="text" id="cdd" name="cdd" placeholder="CDD">
                </div>
                <div class="input-group">
                    <input type="text" id="origem" name="origem" placeholder="ORIGEM">
                    <input type="text" id="editora" name="editora" placeholder="EDITORA">
                </div>
                <div class="input-group">
                    <input type="text" id="local" name="local" placeholder="LOCAL">
                    <input type="text" id="genero" name="genero" placeholder="GÊNERO">
                </div>
                <div class="input-group">
                
                <input type="number" id="ano_aquisicao" name="ano_aquisicao" placeholder="ANO DE AQUISIÇÃO" min="1900" max="<?php echo date('Y'); ?>" required>

                    <select id="numero_registro" name="numero_registro" onchange="fetchBookData()">
                        <option value="">NÚMERO DE REGISTRO</option>
                        <?php
                        // Preencher as opções do número de registro
                        if ($titulo) {
                            $stmt = $conn->prepare("SELECT numero_registro FROM livros WHERE titulo_livro = :titulo");
                            $stmt->bindParam(':titulo', $titulo);
                            $stmt->execute();
                            $registros = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            echo '<option value="todos">Todos</option>';
                            foreach ($registros as $registro) {
                                echo '<option value="' . htmlspecialchars($registro) . '">' . htmlspecialchars($registro) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                <input type="text" id="quantidade_estoque" name="quantidade_estoque" value="<?php echo $totalLivros; ?> (<?php echo $livrosDisponiveis; ?> estão disponíveis para empréstimos)" readonly>

                    <select id="selo" name="selo" class="selec">
                        <option value="">SELO</option>
                        <option value="sim">SIM</option>
                        <option value="não">NÃO</option>
                    </select>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-enviar"><i class="fa-solid fa-check"></i> Enviar</button>
                    <a href="../">
                        <button type="button" class="btn-voltar"><i class="fa-solid fa-arrow-left"></i> Voltar</button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript">
function fetchBookData() {
    var numeroRegistro = document.getElementById('numero_registro').value;
    var tituloLivro = document.getElementById('titulo_livro').value;

    if (numeroRegistro && numeroRegistro !== 'todos') {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_livro.php?numero_registro=' + encodeURIComponent(numeroRegistro), true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                if (data) {
                    document.getElementById('titulo_livro').value = data.titulo_livro || '';
                    document.getElementById('autor').value = data.autor || '';
                    document.getElementById('cdu').value = data.cdu || '';
                    document.getElementById('cdd').value = data.cdd || '';
                    document.getElementById('origem').value = data.origem || '';
                    document.getElementById('editora').value = data.editora || '';
                    document.getElementById('local').value = data.local || '';
                    document.getElementById('genero').value = data.genero || '';
                    document.getElementById('ano_aquisicao').value = data.ano_aquisicao || '';
                    document.getElementById('quantidade_estoque').value = data.quantidade_estoque || '';
                    document.getElementById('selo').value = data.selo || '';
                }
            }
        };
        xhr.send();
    } else if (numeroRegistro === 'todos' && tituloLivro) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_livro.php?titulo_livro=' + encodeURIComponent(tituloLivro) + '&numero_registro=todos', true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                if (data) {
                    document.getElementById('titulo_livro').value = data.titulo_livro || '';
                    document.getElementById('autor').value = data.autor || '';
                    document.getElementById('cdu').value = data.cdu || '';
                    document.getElementById('cdd').value = data.cdd || '';
                    document.getElementById('origem').value = data.origem || '';
                    document.getElementById('editora').value = data.editora || '';
                    document.getElementById('local').value = data.local || '';
                    document.getElementById('genero').value = data.genero || '';
                    document.getElementById('ano_aquisicao').value = data.ano_aquisicao || '';
                    document.getElementById('quantidade_estoque').value = data.quantidade_estoque || '';
                    document.getElementById('selo').value = data.selo || '';
                }
            }
        };
        xhr.send();
    } else {
        // Limpar os campos se necessário
        document.getElementById('titulo_livro').value = '';
        document.getElementById('autor').value = '';
        document.getElementById('cdu').value = '';
        document.getElementById('cdd').value = '';
        document.getElementById('origem').value = '';
        document.getElementById('editora').value = '';
        document.getElementById('local').value = '';
        document.getElementById('genero').value = '';
        document.getElementById('ano_aquisicao').value = '';
        document.getElementById('quantidade_estoque').value = '';
        document.getElementById('selo').value = '';
    }
}



</script>
<script type="text/javascript" src="scripts.js"></script>
</html>
