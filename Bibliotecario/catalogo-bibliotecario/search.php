<?php
require_once '../../../dependencies/config.php';

// Função para obter a capa do livro
function getBookCover($titulo) {
    global $conn; // Garantir que a variável de conexão está disponível

    $cacheDir = 'cache/';
    $cacheFile = $cacheDir . md5($titulo) . '.jpg';

    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    // Primeiro, procurar a imagem na tabela do banco de dados
    $stmt = $conn->prepare("SELECT imagem FROM livros WHERE titulo_livro = :titulo");
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->execute();
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro && $livro['imagem']) {
        // Se a imagem estiver no banco de dados, salvar o conteúdo da imagem no cache
        $imageData = $livro['imagem'];
        file_put_contents($cacheFile, $imageData);
        return $cacheFile;
    }

    // Se não encontrar no banco de dados, buscar na API
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
            return $cacheFile;
        }
    }

    return 'img/sem-foto.png';
}


$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$livrosPorPagina = 25;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $livrosPorPagina;

$sql = "SELECT DISTINCT titulo_livro FROM livros WHERE titulo_livro LIKE :query LIMIT :limite OFFSET :offset";
$queryParam = '%' . $searchQuery . '%';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':query', $queryParam, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limite', $livrosPorPagina, PDO::PARAM_INT);
$stmt->execute();

$results = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $titulo = $row['titulo_livro'];
    $results[] = [
        'titulo' => $titulo,
        'imagem' => 'img/loading.png' // Placeholder ou imagem padrão inicial
    ];
}

header('Content-Type: application/json');
echo json_encode($results);
?>
