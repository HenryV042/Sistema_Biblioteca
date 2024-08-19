<?php
require_once 'config.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Imprimir informações de login e senha para fins de depuração
    echo "Usuário: $usuario<br>";
    echo "Senha de entrada: $senha<br>";

    // Buscar o usuário no banco de dados
    $stmt = $conn->prepare('SELECT * FROM bibliotecario WHERE usuario = :usuario AND senha = :senha');
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    $resultado = $stmt->fetch();

    // Verificar se o usuário existe
    if ($resultado) {
        // Login bem-sucedido, redirecionar para a página de destino
        header('Location: index.php');
        exit;
    } else {
        // Login falhou, exibir mensagem de erro
        echo 'Usuário ou senha inválidos';
    }
}

?>
