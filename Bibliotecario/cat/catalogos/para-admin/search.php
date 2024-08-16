<?php
require_once 'dependencies/config.php';

// Função para obter a capa do livro
function getBookCover($titulo)
{
    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;
    $cacheDir = dirname(__DIR__) . '/cache/'; // Caminho relativo ao diretório do script
    $cacheFile = $cacheDir . md5($titulo) . '.jpg';

    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true); // Permissões mais restritivas
    }

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
        return 'img/sem-foto.png'; // Retorna uma imagem padrão em caso de erro
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

    return 'img/sem-foto.png'; // Retorna uma imagem padrão se não encontrar a capa
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
    $imagem = getBookCover($titulo);
    $results[] = [
        'titulo' => $titulo,
        'imagem' => $imagem
    ];
}

header('Content-Type: application/json');
echo json_encode($results);
?>
