<?php
require_once '../../../dependencies/config.php';

function getBookCover($titulo)
{
    global $conn;
    $cacheDir = 'cache/';
    $cacheFile = $cacheDir . md5($titulo) . '.jpg';

    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    if (file_exists($cacheFile)) {
        return $cacheFile;
    }

    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return 'img/sem-foto.png';
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        $imageUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        $imageData = file_get_contents($imageUrl);

        if ($imageData !== false) {
            file_put_contents($cacheFile, $imageData);
            $stmt = $conn->prepare("UPDATE livros SET imagem = :imagem WHERE titulo_livro = :titulo");
            $stmt->bindParam(':imagem', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->execute();
            return $cacheFile;
        }
    }

    return 'img/sem-foto.png';
}

$sqlGeneros = "SELECT DISTINCT genero FROM livros ORDER BY genero";
$stmtGeneros = $conn->query($sqlGeneros);
$generos = $stmtGeneros->fetchAll(PDO::FETCH_COLUMN);

$livrosPorPagina = 25;
$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $livrosPorPagina;
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$selectedGenero = isset($_GET['genero']) ? $_GET['genero'] : '';

$sql = "SELECT DISTINCT titulo_livro FROM livros WHERE 1=1";
$params = [];

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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="scripts.js"></script>
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" onclick="fecharMenu()">&times;</button>
                </div>
                <div class="linha"></div>
                <div class="opcoes">
                    <a href="../../sair" class="sair"><i class="fa-solid fa-circle-xmark"></i> Sair</a>
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
                        <img
                            src="https://s3-alpha-sig.figma.com/img/2fbc/cd73/5f61f04407b960f9f22ea475bc2a6622?Expires=1724025600&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=PHaJAoNOMUlkVfRi3HpcnO9RJMN0kOv5RZKQy-5GYOGobxnW-w6M6JJhE-9x2eYJ8v8hXr82pqOla5RaiZ0ASX3OaMXike1yLP1Gn2NG8-on1MJNnttlODlc3OMYHYv968JQvY-iA9d36RbcI7jJwAnfAhTmkZkuLyAjd4RYBt1phUHQ5rHAwdjMf9-0QYMNjiQVSG23kReG1qEG~1AiYRBR-iDXozK1v9~KRe0x-0B6jpNk3xPbscoLEf7Cueht5vrUMn0HGvnJP2Mnza6x-gumYqlch9EDRSgctOGIdS1p6kGd93j1y-CWrW0Ggtto43oKMAunWQxNInldzmFsTQ__" />
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
                        <img src="' . $imagem . '" alt="capa do livro" style="max-width: 100px; max-height: 150px;" loading="lazy">
                        <a href="infor/infor.php?titulo_livro=' . urlencode($titulo) . '"><button class="botao-verde">+ informações</button></a>
                      </div>';
                }
            } else {
                echo '<p>Nenhum livro encontrado.</p>';
            }
            ?>
        </div>
        <!-- Paginação -->
        <!-- Paginação -->
        <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a
                    href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php
            $maxLinks = 5; // Máximo número de links de páginas a serem exibidos
            $start = max(1, $paginaAtual - floor($maxLinks / 2));
            $end = min($totalPaginas, $paginaAtual + floor($maxLinks / 2));

            if ($start > 1) {
                echo '<a href="?q=' . urlencode($searchQuery) . '&genero=' . urlencode($selectedGenero) . '&pagina=1">1</a>';
                if ($start > 2) {
                    echo '<span>...</span>';
                }
            }

            for ($i = $start; $i <= $end; $i++): ?>
                <a href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $i; ?>"
                    <?php if ($i == $paginaAtual)
                        echo 'class="active"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php
            if ($end < $totalPaginas) {
                if ($end < $totalPaginas - 1) {
                    echo '<span>...</span>';
                }
                echo '<a href="?q=' . urlencode($searchQuery) . '&genero=' . urlencode($selectedGenero) . '&pagina=' . $totalPaginas . '">' . $totalPaginas . '</a>';
            }
            ?>

            <?php if ($paginaAtual < $totalPaginas): ?>
                <a
                    href="?q=<?php echo urlencode($searchQuery); ?>&genero=<?php echo urlencode($selectedGenero); ?>&pagina=<?php echo $paginaAtual + 1; ?>">Próximo</a>
            <?php endif; ?>
        </div>

    </section>

    <script>
        document.getElementById('genero').addEventListener('change', function () {
            this.form.submit();
        });
    </script>
    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>