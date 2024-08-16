<!-- <?php
require_once '../dependencies/config.php'; -->

// Recuperar os dados do formulário
$titulo_livro = isset($_POST['titulo_livro']) ? $_POST['titulo_livro'] : '';
$autor = isset($_POST['autor']) ? $_POST['autor'] : '';
$cdu = isset($_POST['cdu']) ? $_POST['cdu'] : '';
$cdd = isset($_POST['cdd']) ? $_POST['cdd'] : '';
$origem = isset($_POST['origem']) ? $_POST['origem'] : '';
$editora = isset($_POST['editora']) ? $_POST['editora'] : '';
$local = isset($_POST['local']) ? $_POST['local'] : '';
$genero = isset($_POST['genero']) ? $_POST['genero'] : '';
$ano_aquisicao = isset($_POST['ano_aquisicao']) ? $_POST['ano_aquisicao'] : '';
$numero_registro = isset($_POST['numero_registro']) ? $_POST['numero_registro'] : '';
$selo = isset($_POST['selo']) ? $_POST['selo'] : '';

// Validar se o título do livro foi fornecido
if (empty($titulo_livro)) {
    echo json_encode(['success' => false, 'error' => 'Título do livro é obrigatório.']);
    exit;
}

// Validar se o número de registro foi fornecido
if (empty($numero_registro)) {
    echo json_encode(['success' => false, 'error' => 'Número de registro é obrigatório.']);
    exit;
}

try {
    if ($numero_registro === 'todos') {
        // Atualizar todos os livros com o mesmo título
        $sql = "UPDATE livros SET titulo_livro = :titulo_livro, autor = :autor, editora = :editora, ano_aquisicao = :ano_aquisicao, origem = :origem, local = :local, genero = :genero, cdd = :cdd, cdu = :cdu, selo = :selo WHERE titulo_livro = :titulo_livro";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titulo_livro' => $titulo_livro,
            ':autor' => $autor,
            ':editora' => $editora,
            ':ano_aquisicao' => $ano_aquisicao,
            ':origem' => $origem,
            ':local' => $local,
            ':genero' => $genero,
            ':cdd' => $cdd,
            ':cdu' => $cdu,
            ':selo' => $selo
        ]);
    } else {
        // Atualizar o livro específico pelo número de registro
        $sql = "UPDATE livros SET titulo_livro = :titulo_livro, autor = :autor, editora = :editora, ano_aquisicao = :ano_aquisicao, origem = :origem, local = :local, genero = :genero, cdd = :cdd, cdu = :cdu, selo = :selo WHERE numero_registro = :numero_registro";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titulo_livro' => $titulo_livro,
            ':autor' => $autor,
            ':editora' => $editora,
            ':ano_aquisicao' => $ano_aquisicao,
            ':origem' => $origem,
            ':local' => $local,
            ':genero' => $genero,
            ':cdd' => $cdd,
            ':cdu' => $cdu,
            ':selo' => $selo,
            ':numero_registro' => $numero_registro
        ]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao atualizar os dados: ' . $e->getMessage()]);
}
?>
