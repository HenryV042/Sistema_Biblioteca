<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php");
    exit();
}

require_once '../../../dependencies/config.php';

$numeroRegistro = isset($_GET['numero_registro']) ? $_GET['numero_registro'] : '';
$tituloLivro = isset($_GET['titulo_livro']) ? $_GET['titulo_livro'] : '';

function getBookData($numeroRegistro)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM livros WHERE numero_registro = :numero_registro");
    $stmt->bindParam(':numero_registro', $numeroRegistro);
    $stmt->execute();
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro) {
        $titulo = $livro['titulo_livro'];

        // Buscar o total de livros e a quantidade disponível
        $stmt = $conn->prepare("SELECT COUNT(*) FROM livros WHERE titulo_livro = :titulo_livro");
        $stmt->bindParam(':titulo_livro', $titulo);
        $stmt->execute();
        $totalLivros = $stmt->fetchColumn();

        $stmt = $conn->prepare("
            SELECT COUNT(*) FROM emprestimos 
            WHERE titulo_livro = :titulo_livro AND data_devolucao IS NULL
        ");
        $stmt->bindParam(':titulo_livro', $titulo);
        $stmt->execute();
        $livrosEmprestados = $stmt->fetchColumn();

        $livrosDisponiveis = $totalLivros - $livrosEmprestados;

        $livro['quantidade_estoque'] = "$totalLivros ($livrosDisponiveis estão disponíveis para empréstimos)";

        // Codificar a imagem para base64 se houver
        if (!empty($livro['imagem'])) {
            $livro['imagem'] = base64_encode($livro['imagem']);
        }
    }

    return $livro;
}


header('Content-Type: application/json');

try {
    if ($numeroRegistro) {
        // Se o número de registro for fornecido, buscar informações do livro correspondente
        $livroData = getBookData($numeroRegistro);
        echo json_encode($livroData);
    } elseif ($tituloLivro && $numeroRegistro === 'todos') {
        // Se o título do livro for fornecido e o número de registro for 'todos', buscar o primeiro livro com o título
        $stmt = $conn->prepare("SELECT * FROM livros WHERE titulo_livro = :titulo_livro LIMIT 1");
        $stmt->bindParam(':titulo_livro', $tituloLivro);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($livro) {
            $numeroRegistro = $livro['numero_registro'];
            $livroData = getBookData($numeroRegistro);
            echo json_encode($livroData);
        } else {
            // Se nenhum livro for encontrado com o título fornecido
            echo json_encode([]);
        }
    } else {
        // Se não for fornecido número de registro nem título do livro
        echo json_encode([]);
    }
} catch (Exception $e) {
    // Log do erro para depuração
    error_log("Erro na execução do script: " . $e->getMessage());
    echo json_encode([]);
}
?>
