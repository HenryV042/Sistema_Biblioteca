<?php
session_start();

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

// Conectar ao banco de dados
require_once('../../dependencies/config.php');

// Preparar a consulta SQL com placeholders
$query = $conn->prepare("SELECT * FROM bibliotecario WHERE usuario = :usuario AND senha = :senha");
$query->bindValue(':usuario', $usuario);

// Aplicar hash na senha para correspondência segura
$query->bindValue(':senha', $senha);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $_SESSION['usuario'] = $usuario;
    header("Location: ../ranking/index.php"); // Redireciona para a página inicial após o login
    exit();
} else {
    echo "Usuário ou senha incorretos.";
}
?>
