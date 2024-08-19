<?php
// emprestimos.php

require_once 'config.php';

try {
    $stmt = $conn->prepare("SELECT
    a.id AS aluno_id,
    a.nome_completo AS nome_aluno,  -- Nome completo do aluno
    a.curso,
    a.serie,
    e.id AS emprestimo_id,
    e.titulo_livro,
    e.numero_registro,
    e.data_emprestimo,
    e.data_rascunho,
    e.status,
    DATEDIFF(CURDATE(), COALESCE(e.data_rascunho, e.data_emprestimo)) AS dias_decorridos,
    DATE_ADD(COALESCE(e.data_rascunho, e.data_emprestimo), INTERVAL 7 DAY) AS prazo
FROM
    aluno a
JOIN
    emprestimos e ON a.id = e.aluno_id
WHERE
    -- Verifica se a data_rascunho é nula ou não
    (e.data_rascunho IS NOT NULL AND DATEDIFF(CURDATE(), e.data_rascunho) > 7)
    OR
    (e.data_rascunho IS NULL AND DATEDIFF(CURDATE(), e.data_emprestimo) > 7)
ORDER BY
    a.curso,
    a.serie,
    a.nome_completo;");
    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($emprestimos);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>