<?php
require_once '../../../../dependencies/config.php';

// Função para calcular o total de livros e a quantidade disponível
function getBookData($conn, $titulo)
{
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

// Verifica se o número do registro foi fornecido e não é "todos"
if (isset($_GET['numero_registro']) && $_GET['numero_registro'] !== 'todos') {
    $numeroRegistro = $_GET['numero_registro'];
    
    // Recupera os dados do livro com base no número do registro
    $stmt = $conn->prepare("SELECT * FROM livros WHERE numero_registro = :numero_registro");
    $stmt->bindParam(':numero_registro', $numeroRegistro);
    $stmt->execute();
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro) {
        // Adiciona a quantidade total e disponível ao resultado
        list($totalLivros, $livrosDisponiveis) = getBookData($conn, $livro['titulo_livro']);
        $livro['quantidade_estoque'] = "$totalLivros ($livrosDisponiveis estão disponíveis para empréstimos)";
        header('Content-Type: application/json');
        echo json_encode($livro);
    } else {
        echo json_encode([]);
    }
} elseif (isset($_GET['titulo_livro']) && $_GET['numero_registro'] === 'todos') {
    // Se "todos" for selecionado, buscar o primeiro livro com o mesmo título
    $tituloLivro = $_GET['titulo_livro'];
    $stmt = $conn->prepare("SELECT * FROM livros WHERE titulo_livro = :titulo_livro LIMIT 1");
    $stmt->bindParam(':titulo_livro', $tituloLivro);
    $stmt->execute();
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro) {
        list($totalLivros, $livrosDisponiveis) = getBookData($conn, $tituloLivro);
        $livro['quantidade_estoque'] = "$totalLivros ($livrosDisponiveis estão disponíveis para empréstimos)";
        header('Content-Type: application/json');
        echo json_encode($livro);
    } else {
        echo json_encode([]);
    }
} else {
    // Se não houver parâmetros válidos, retornar um JSON vazio
    echo json_encode([]);
}
?>
