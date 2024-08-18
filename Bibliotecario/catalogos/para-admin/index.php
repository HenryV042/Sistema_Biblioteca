<?php
require_once '../../../dependencies/config.php';

// Função para buscar a URL da capa do livro usando a Google Books API com cURL
function getBookCover($titulo)
{
    global $conn; // Usar a conexão global para consultar o banco de dados

    $cacheDir = 'cache/';
    $cacheFile = $cacheDir . md5($titulo) . '.jpg';

    // Verifica se a pasta de cache existe, se não, cria
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    // Verifica se a imagem está em cache
    if (file_exists($cacheFile)) {
        return $cacheFile;
    }

    // Verifica se a imagem já está no banco de dados
    $stmt = $conn->prepare("SELECT imagem FROM livros WHERE titulo_livro = :titulo");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->execute();
    $imagemData = $stmt->fetchColumn();

    if ($imagemData) {
        // Cria um arquivo temporário para a imagem
        file_put_contents($cacheFile, $imagemData);
        return $cacheFile;
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
        return 'img/sem-foto.png'; // Retorna uma imagem padrão em caso de erro
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

    return 'img/sem-foto.png'; // Retorna uma imagem padrão se não encontrar a capa
}

// Obter gêneros disponíveis
$sqlGeneros = "SELECT DISTINCT genero FROM livros ORDER BY genero";
$stmtGeneros = $conn->query($sqlGeneros);
$generos = $stmtGeneros->fetchAll(PDO::FETCH_COLUMN);

// Parâmetros para paginação e filtragem
$livrosPorPagina = 25;
$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $livrosPorPagina;
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$selectedGenero = isset($_GET['genero']) ? $_GET['genero'] : '';

// Preparar consulta SQL
$sql = "SELECT DISTINCT titulo_livro FROM livros WHERE 1=1";
$params = [];

// Adicionar filtros de pesquisa
if ($searchQuery) {
    $sql .= " AND titulo_livro LIKE :query";
    $params[':query'] = '%' . $searchQuery . '%';
}

if ($selectedGenero) {
    $sql .= " AND genero = :genero";
    $params[':genero'] = $selectedGenero;
}

$sql .= " LIMIT :limite OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limite', $livrosPorPagina, PDO::PARAM_INT);

if (isset($params[':query'])) {
    $stmt->bindParam(':query', $params[':query'], PDO::PARAM_STR);
}

if (isset($params[':genero'])) {
    $stmt->bindParam(':genero', $params[':genero'], PDO::PARAM_STR);
}

$stmt->execute();

// Obter total de livros
$sqlTotal = "SELECT COUNT(DISTINCT titulo_livro) as total FROM livros WHERE 1=1";
if (isset($params[':query'])) {
    $sqlTotal .= " AND titulo_livro LIKE :query";
}

if (isset($params[':genero'])) {
    $sqlTotal .= " AND genero = :genero";
}

$stmtTotal = $conn->prepare($sqlTotal);
if (isset($params[':query'])) {
    $stmtTotal->bindParam(':query', $params[':query'], PDO::PARAM_STR);
}

if (isset($params[':genero'])) {
    $stmtTotal->bindParam(':genero', $params[':genero'], PDO::PARAM_STR);
}

$stmtTotal->execute();
$totalLivros = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalLivros / $livrosPorPagina);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/style-bibli.css">
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="Logo Menu" class="logoMenu">
                    <button class="fechar" onclick="fecharMenu()">&times;</button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <<a href="../../registrarlivro"><i class="fa-solid fa-file"></i> Cadastrar Livro</a>
                        <a href="../../cad-emprestimo"><i class="fa-solid fa-book-open-reader"></i> Cadastrar
                            Empréstimo</a>
                        <a href="../../catalogos"><i class="fa-solid fa-book-bookmark"></i> Banco de Livros</a>
                        <a href="../../emprestimos"><i class="fa-brands fa-leanpub"></i> Empréstimos</a>
                        <a href="../../turmas"><i class="fa-solid fa-user-plus"></i> Adicionar Turma</a>
                        <a href="../../pedidos"><i class="fa-solid fa-address-book"></i> Pedidos</a>
                        <a href="../../ranking"><i class="fa-solid fa-file-import"></i> Relatório</a>
                        <a href="../../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i> Sair</a>
                </div>
            </aside>
            <section id="principal">
                <span style="font-size:43px;cursor:pointer" onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img src="img/logoEEEP.png" alt="Logo EEEP" class="logo_eeep" />
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="Logo Biblioteca" class="library" />
                </div>
            </section>
        </nav>
    </header>

    <section class="shop-section">
        <div class="header-biblioteca">
            <div class="restrito">
                <span class="ClasseTexto">BIBLIOTECA DE LIVROS</span>
            </div>
            <form method="GET" action="">
                <div id="divBusca">
                    <select name="genero" id="genero">
                        <option value="">Todos os Gêneros</option>
                        <?php
                        foreach ($generos as $genero) {
                            $selected = ($genero == $selectedGenero) ? 'selected' : '';
                            echo "<option value=\"$genero\" $selected>$genero</option>";
                        }
                        ?>
                    </select>
                    <input type="text" id="txtBusca" name="q" value="<?php echo htmlspecialchars($searchQuery); ?>"
                        placeholder="Buscar livros...">
                    <button id="btnBusca" type="submit">
                        <img src="img/search-icon.png" alt="Buscar">
                    </button>
                </div>
            </form>
        </div>
        <div class="barra-branca"></div>
        <div class="shop-images" id="search-results">
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $titulo = htmlspecialchars($row["titulo_livro"]);
                    $imagem = getBookCover($titulo);

                    echo '<div class="shop-link">
                        <h3>' . $titulo . '</h3>
                        <img src="' . $imagem . '" alt="Capa do livro" style="max-width: 100px; max-height: 150px;" loading="lazy">
                        <a href="infor-admin/infor.php?titulo_livro=' . urlencode($titulo) . '"><button class="botao-verde">+ informações</button></a>
                      </div>';
                }
            } else {
                echo '<p>Nenhum livro encontrado.</p>';
            }
            ?>
        </div>
        <!-- Paginação -->
        <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a
                    href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $i; ?>"
                    <?php if ($i == $paginaAtual)
                        echo 'class="active"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaAtual < $totalPaginas): ?>
                <a
                    href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $paginaAtual + 1; ?>">Próximo</a>
            <?php endif; ?>
        </div>
    </section>

    <script>
        document.getElementById('genero').addEventListener('change', function () {
            this.form.submit(); // Submete o formulário quando o gênero é alterado
        });
    </script>
    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>