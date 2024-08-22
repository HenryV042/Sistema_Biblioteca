<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php");
    exit();
}

require_once '../../../dependencies/config.php';

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

function getBookCover($titulo)
{
    global $conn;

    // Verifica se a imagem já está no banco de dados
    $stmt = $conn->prepare("SELECT imagem FROM livros WHERE titulo_livro = :titulo AND imagem IS NOT NULL ORDER BY id DESC LIMIT 1");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->execute();
    $imagemData = $stmt->fetchColumn();

    if ($imagemData) {
        // Transforma o BLOB em base64
        $base64 = base64_encode($imagemData);
        return 'data:image/jpeg;base64,' . $base64; // Ajuste o tipo de imagem se necessário
    }

    // Se não há imagem no banco de dados, busca na Google Books API
    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return 'data:image/png;base64,' . base64_encode(file_get_contents('../img/sem-foto.png')); // Retorna uma imagem padrão em base64
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        $imageUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        $imageData = file_get_contents($imageUrl);

        if ($imageData !== false) {
            $base64 = base64_encode($imageData);
            // Atualiza o banco de dados com o caminho da imagem (ou pode usar diretamente a base64)
            $stmt = $conn->prepare("UPDATE livros SET imagem = :imagem WHERE titulo_livro = :titulo");
            $stmt->bindParam(':imagem', $base64);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->execute();
            return 'data:image/jpeg;base64,' . $base64; // Ajuste o tipo de imagem se necessário
        }
    }

    return 'data:image/png;base64,' . base64_encode(file_get_contents('../img/sem-foto.png')); // Retorna uma imagem padrão em base64
}



// Buscar a capa do livro
$imagem = getBookCover($titulo);

// Configura a URL da imagem para a tag <img> no HTML
$imageUrl = ($imagem === '../img/sem-foto.png') ? 'src="../../src/assets/ImageNotFound.png"' : 'src="' . $imagem . '"';
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
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="logo" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library" />
                </div>
            </section>
        </nav>
    </header>
    <div class="container">
        <div class="img-livro">
            <img src="<?php echo $imagem; ?>" alt="Capa do Livro">
        </div>
        
        <div class="formulario">
            <form method="POST" action="update_livro.php" enctype="multipart/form-data">
                <h2>MAIS INFORMAÇÕES</h2>
                <!-- inputs e selects -->
                <label for="titulo_livro" class="editar_label">NOME DO LIVRO</label>
                <input type="text" id="titulo_livro" name="titulo_livro" placeholder="NOME DO LIVRO" value="<?php echo htmlspecialchars($titulo); ?>" required>

                <label for="autor" class="editar_label">AUTOR</label>
                <input type="text" id="autor" name="autor" placeholder="AUTOR" required>

                <div class="input-group-label">
                    <label for="cdu" class="editar_label">CDU</label>
                    <label for="cdd" class="editar_label">CDD</label>
                </div>
                <div class="input-group">
                    <input type="text" id="cdu" name="cdu" placeholder="CDU">
                    <input type="text" id="cdd" name="cdd" placeholder="CDD">
                </div>
                <div class="input-group-label">
                    <label for="origem" class="editar_label">ORIGEM</label>
                    <label for="editora" class="editar_label">EDITORA</label>
                </div>
                <div class="input-group">
                    <input type="text" id="origem" name="origem" placeholder="ORIGEM">
                    <input type="text" id="editora" name="editora" placeholder="EDITORA">
                </div>
                <div class="input-group-label">
                    <label for="local" class="editar_label">LOCAL</label>
                    <label for="genero" class="editar_label">GÊNERO</label>
                </div>
                <div class="input-group">
                    <input type="text" id="local" name="local" placeholder="LOCAL">
                    <input type="text" id="genero" name="genero" placeholder="GÊNERO">
                </div>
                <div class="input-group-label">
                    <label for="ano_aquisicao" class="editar_label">ANO DE AQUISIÇÃO</label>
                    <label for="numero_registro" class="editar_label">NÚMERO DE REGISTRO</label>
                </div>
                <div class="input-group">
                    <input type="text" id="ano_aquisicao" name="ano_aquisicao" placeholder="ANO DE AQUISIÇÃO">
                    <select id="numero_registro" name="numero_registro" onchange="fetchBookData()">
                        <option value="todos">SELECIONE O NÚMERO DE REGISTRO</option>
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
                <div class="input-group-label">
                    <label for="estoque">ESTOQUE</label>
                    <label for="selo">SELO</label>
                </div>
                <div class="input-group">
                    <input type="text" id="quantidade_estoque" name="quantidade_estoque" value="<?php echo $totalLivros; ?> (<?php echo $livrosDisponiveis; ?> estão disponíveis para empréstimos)" readonly>
                    <select id="selo" name="selo" class="selec">
                        <option value="S">SIM</option>
                        <option value="N">NÃO</option>
                    </select>
             
                </div>
                   <div>
                    <label for="imagem" class="editar_label">IMAGEM:</label>
                    <input type="file" id="imagem" name="imagem">
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-enviar" onclick="return confirmarAtualizacao()"><i class="fa-solid fa-check"></i> Enviar</button>
                    <a href="../">
                        <button type="button" class="btn-voltar"><i class="fa-solid fa-arrow-left"></i> Voltar</button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

<script type="text/javascript">
    function confirmarAtualizacao() {
        return confirm('Você realmente deseja atualizar os dados do livro?');
    }

function fetchBookData() {
    var numeroRegistro = document.getElementById('numero_registro').value;
    var tituloLivro = document.getElementById('titulo_livro').value;

    function processJsonResponse(xhr) {
        if (xhr.status >= 200 && xhr.status < 300) {
            var data = xhr.response; // Acessa a resposta diretamente, pois responseType é 'json'
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
                
                // Atualiza a imagem do livro
                document.getElementById('livro-imagem');
            }
        } else {
            console.log('Erro na requisição');
        }
    }

    function clearFields() {
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
        document.getElementById('livro-imagem').src = '../img/sem-foto.png'; // Imagem padrão
    }

    var xhr = new XMLHttpRequest();
    
    if (numeroRegistro && numeroRegistro !== 'todos') {
        xhr.open('GET', 'get_livro.php?numero_registro=' + encodeURIComponent(numeroRegistro), true);
        xhr.responseType = 'json'; // Define o tipo de resposta como JSON
        xhr.onload = function () {
            processJsonResponse(xhr);
        };
        xhr.onerror = function () {
            console.log('Erro na requisição:', xhr.statusText);
        };
        xhr.send();
    } else if (numeroRegistro === 'todos' && tituloLivro) {
        xhr.open('GET', 'get_livro.php?titulo_livro=' + encodeURIComponent(tituloLivro) + '&numero_registro=todos', true);
        xhr.responseType = 'json'; // Define o tipo de resposta como JSON
        xhr.onload = function () {
            processJsonResponse(xhr);
        };
        xhr.onerror = function () {
            console.log('Erro na requisição:', xhr.statusText);
        };
        xhr.send();
    } else {
        // Limpar os campos se necessário
        clearFields();
    }
}



    function abrirMenu() {
        var menu = document.getElementById('menu-Oculto');
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }
</script>
<script type="text/javascript" src="scripts.js"></script>

</html>
