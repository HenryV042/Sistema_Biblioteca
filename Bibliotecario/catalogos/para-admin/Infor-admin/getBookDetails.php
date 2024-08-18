<?php
require_once '../../../../dependencies/config.php';

// Recuperar os parâmetros da URL
$titulo = isset($_GET['titulo_livro']) ? $_GET['titulo_livro'] : '';
$numero_registro = isset($_GET['numero_registro']) ? $_GET['numero_registro'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

function getBookCover($titulo)
{
    $titulo = urlencode($titulo);
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . $titulo;

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
        return $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
    }

    return '../img/sem-foto.png'; // Retorna uma imagem padrão se não encontrar a capa
}

try {
    if ($tipo === 'todos') {
        $sql = "SELECT * FROM livros WHERE titulo_livro = :titulo_livro LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':titulo_livro', $titulo);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($livro) {
            $sqlEstoque = "SELECT COUNT(*) AS total_livros FROM livros WHERE titulo_livro = :titulo_livro";
            $stmtEstoque = $conn->prepare($sqlEstoque);
            $stmtEstoque->bindParam(':titulo_livro', $titulo);
            $stmtEstoque->execute();
            $totalLivros = $stmtEstoque->fetchColumn();

            $sqlEmprestimos = "SELECT COUNT(*) AS emprestados FROM emprestimos WHERE titulo_livro = :titulo_livro AND status = 'emprestado'";
            $stmtEmprestimos = $conn->prepare($sqlEmprestimos);
            $stmtEmprestimos->bindParam(':titulo_livro', $titulo);
            $stmtEmprestimos->execute();
            $emprestados = $stmtEmprestimos->fetchColumn();

            $estoqueDisponivel = $totalLivros - $emprestados;

            $livro['imagem'] = getBookCover($livro['titulo_livro']);
            $livro['total_livros'] = $totalLivros;
            $livro['estoque_disponivel'] = $estoqueDisponivel;

            echo json_encode($livro);
        } else {
            echo json_encode(['error' => 'Nenhum livro encontrado com o título fornecido.']);
        }
    } else {
        $sql = "SELECT titulo_livro, autor, editora, ano_aquisicao, origem, local, genero, cdd, cdu, selo 
                FROM livros WHERE numero_registro = :numero_registro";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':numero_registro', $numero_registro);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($livro) {
            $sqlEstoque = "SELECT COUNT(*) AS total_livros FROM livros WHERE numero_registro = :numero_registro";
            $stmtEstoque = $conn->prepare($sqlEstoque);
            $stmtEstoque->bindParam(':numero_registro', $numero_registro);
            $stmtEstoque->execute();
            $totalLivros = $stmtEstoque->fetchColumn();

            $sqlEmprestimos = "SELECT COUNT(*) AS emprestados FROM emprestimos WHERE numero_registro = :numero_registro AND status = 'emprestado'";
            $stmtEmprestimos = $conn->prepare($sqlEmprestimos);
            $stmtEmprestimos->bindParam(':numero_registro', $numero_registro);
            $stmtEmprestimos->execute();
            $emprestados = $stmtEmprestimos->fetchColumn();

            $estoqueDisponivel = $totalLivros - $emprestados;

            $livro['imagem'] = getBookCover($livro['titulo_livro']);
            $livro['total_livros'] = $totalLivros;
            $livro['estoque_disponivel'] = $estoqueDisponivel;

            echo json_encode($livro);
        } else {
            echo json_encode(['error' => 'Nenhum livro encontrado com o número de registro fornecido.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro na consulta: ' . $e->getMessage()]);
}
?>
