<?php
require_once '../../dependencies/config.php';

$mesInicial = isset($_POST['mes_inicial']) ? $_POST['mes_inicial'] : '';
$mesFinal = isset($_POST['mes_final']) ? $_POST['mes_final'] : '';
$curso = isset($_POST['curso']) ? $_POST['curso'] : '';

$mesInicialCondition = $mesInicial ? "MONTH(e.data_emprestimo) >= :mesInicial" : "1";
$mesFinalCondition = $mesFinal ? "MONTH(e.data_emprestimo) <= :mesFinal" : "1";

// Consulta para livros
$sqlLivros = "SELECT e.titulo_livro AS livro, COUNT(e.id) AS total_leituras
              FROM emprestimos e
              JOIN aluno a ON e.aluno_id = a.id
              WHERE $mesInicialCondition
              AND $mesFinalCondition
              AND (:curso = '' OR a.curso = :curso)
              GROUP BY e.titulo_livro
              ORDER BY total_leituras DESC
              LIMIT 5";

$stmtLivros = $conn->prepare($sqlLivros);
$paramsLivros = [];
if ($mesInicial) $paramsLivros[':mesInicial'] = $mesInicial;
if ($mesFinal) $paramsLivros[':mesFinal'] = $mesFinal;
if ($curso) $paramsLivros[':curso'] = $curso;
$stmtLivros->execute($paramsLivros);
$livros = $stmtLivros->fetchAll(PDO::FETCH_ASSOC);

// Consulta para cursos
$sqlCursos = "SELECT t.nome_identificacao AS turma, COUNT(e.id) AS total_leituras
              FROM emprestimos e
              JOIN aluno a ON e.aluno_id = a.id
              JOIN turma t ON a.curso = t.curso AND a.serie = t.serie
              WHERE $mesInicialCondition
              AND $mesFinalCondition
              AND (:curso = '' OR a.curso = :curso)
              GROUP BY t.nome_identificacao
              ORDER BY total_leituras DESC
              LIMIT 5";

$stmtCursos = $conn->prepare($sqlCursos);
$stmtCursos->execute($paramsLivros);
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'livros' => $livros,
    'cursos' => $cursos
]);
?>
