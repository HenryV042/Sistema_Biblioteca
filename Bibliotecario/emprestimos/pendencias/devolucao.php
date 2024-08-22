<?php
require_once '../../../dependencies/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("UPDATE emprestimos SET data_devolucao = NOW()  WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: index.php");
    exit;
} else {
    echo "ID inválido.";
}
?>
