<?php
// emprestimos.php

require_once 'config.php';

try {
    $stmt = $conn->prepare("SELECT e.*, a.nome_completo AS nome_aluno 
                            FROM emprestimos e 
                            INNER JOIN aluno a ON e.aluno_id = a.id 
                            WHERE e.data_devolucao is null");
    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($emprestimos);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>