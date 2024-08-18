<?php
// Conectar ao banco de dados
require_once '../../../dependencies/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $matricula = $_POST['matricula'] ?? '';
    $numero_registro = $_POST['numero_registro'] ?? '';

    echo '<h1>Pedido Aceito</h1>';
    echo '<p><strong>Matrícula:</strong> ' . htmlspecialchars($matricula) . '</p>';
    echo '<p><strong>Número de Registro do Livro:</strong> ' . htmlspecialchars($numero_registro) . '</p>';
} else {
    echo 'Método de solicitação inválido.';
}
?>
