<?php
require_once '../../../dependencies/config.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar dados do formulário
    $titulo_livro = $_POST['titulo_livro'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $cdu = $_POST['cdu'] ?? '';
    $cdd = $_POST['cdd'] ?? '';
    $origem = $_POST['origem'] ?? '';
    $editora = $_POST['editora'] ?? '';
    $local = $_POST['local'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $ano_aquisicao = $_POST['ano_aquisicao'] ?? '';
    $numero_registro = $_POST['numero_registro'] ?? '';
    $selo = $_POST['selo'] ?? '';

    // Lidar com o upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagemTmpName = $_FILES['imagem']['tmp_name'];
        $imagemData = file_get_contents($imagemTmpName);

        // Atualizar a imagem no banco de dados
        $stmt = $conn->prepare("UPDATE livros SET imagem = :imagem WHERE titulo_livro = :titulo_livro");
        $stmt->bindParam(':imagem', $imagemData, PDO::PARAM_LOB);
        $stmt->bindParam(':titulo_livro', $titulo_livro);
        $stmt->execute();
    }

    // Atualiza todos os registros com o mesmo título
    if ($numero_registro === 'todos') {
        $stmt = $conn->prepare("UPDATE livros SET autor = :autor, cdu = :cdu, cdd = :cdd, origem = :origem, editora = :editora, local = :local, genero = :genero, ano_aquisicao = :ano_aquisicao, selo = :selo WHERE titulo_livro = :titulo_livro");
        $stmt->bindParam(':titulo_livro', $titulo_livro);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':cdu', $cdu);
        $stmt->bindParam(':cdd', $cdd);
        $stmt->bindParam(':origem', $origem);
        $stmt->bindParam(':editora', $editora);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':ano_aquisicao', $ano_aquisicao);
        $stmt->bindParam(':selo', $selo);
        $stmt->execute();
    } else {
        // Atualiza o registro específico
        $stmt = $conn->prepare("UPDATE livros SET autor = :autor, cdu = :cdu, cdd = :cdd, origem = :origem, editora = :editora, local = :local, genero = :genero, ano_aquisicao = :ano_aquisicao, selo = :selo WHERE titulo_livro = :titulo_livro AND numero_registro = :numero_registro");
        $stmt->bindParam(':titulo_livro', $titulo_livro);
        $stmt->bindParam(':numero_registro', $numero_registro);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':cdu', $cdu);
        $stmt->bindParam(':cdd', $cdd);
        $stmt->bindParam(':origem', $origem);
        $stmt->bindParam(':editora', $editora);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':ano_aquisicao', $ano_aquisicao);
        $stmt->bindParam(':selo', $selo);
        $stmt->execute();
    }

    // Redireciona ou exibe uma mensagem de sucesso
    header('Location: ../index.php');
    exit;
}
?>
