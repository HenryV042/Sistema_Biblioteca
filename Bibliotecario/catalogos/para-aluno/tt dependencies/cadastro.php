<?php
require_once 'config.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Criar a hash da senha
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir o usuário no banco de dados
    $stmt = $conn->prepare('INSERT INTO bibliotecario (usuario, senha) VALUES (:usuario, :senha)');
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Senha inserida com sucesso
        header('Location: ../index.php');
        exit;
    } else {
        // Erro ao inserir senha
        echo 'Erro ao cadastrar usuário';
    }
}
?>