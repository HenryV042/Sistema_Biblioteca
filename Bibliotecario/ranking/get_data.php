<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/index.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

require_once '../../dependencies/config.php';

// Recuperar filtros do POST
$mesInicial = isset($_POST['mes_inicial']) ? $_POST['mes_inicial'] : '';
$mesFinal = isset($_POST['mes_final']) ? $_POST['mes_final'] : '';
$curso = isset($_POST['curso']) ? $_POST['curso'] : '';

try {
    // Criar a consulta base
    $sql = "
        SELECT e.titulo_livro AS livro, COUNT(e.id) AS total_leituras
        FROM emprestimos e
        JOIN aluno a ON e.aluno_id = a.id
        WHERE 1=1
    ";

    // Adicionar condições de filtros
    if (!empty($mesInicial) && !empty($mesFinal)) {
        $sql .= " AND MONTH(e.data_emprestimo) BETWEEN :mes_inicial AND :mes_final";
    }

    if (!empty($curso)) {
        $sql .= " AND a.curso = :curso";
    }

    $sql .= " GROUP BY e.titulo_livro
              ORDER BY total_leituras DESC
              LIMIT 5";

    $stmt = $conn->prepare($sql);

    // Vincular parâmetros
    if (!empty($mesInicial) && !empty($mesFinal)) {
        $stmt->bindParam(':mes_inicial', $mesInicial, PDO::PARAM_INT);
        $stmt->bindParam(':mes_final', $mesFinal, PDO::PARAM_INT);
    }

    if (!empty($curso)) {
        $stmt->bindParam(':curso', $curso, PDO::PARAM_STR);
    }

    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consultar os cursos
    $sqlCursos = "
        SELECT t.nome_identificacao AS turma, COUNT(e.id) AS total_leituras
        FROM emprestimos e
        JOIN aluno a ON e.aluno_id = a.id
        JOIN turma t ON a.curso = t.curso AND a.serie = t.serie
        WHERE 1=1
    ";

    // Adicionar condições de filtros para cursos
    if (!empty($mesInicial) && !empty($mesFinal)) {
        $sqlCursos .= " AND MONTH(e.data_emprestimo) BETWEEN :mes_inicial AND :mes_final";
    }

    if (!empty($curso)) {
        $sqlCursos .= " AND a.curso = :curso";
    }

    $sqlCursos .= " GROUP BY t.nome_identificacao
                    ORDER BY total_leituras DESC
                    LIMIT 5";

    $stmtCursos = $conn->prepare($sqlCursos);

    // Vincular parâmetros
    if (!empty($mesInicial) && !empty($mesFinal)) {
        $stmtCursos->bindParam(':mes_inicial', $mesInicial, PDO::PARAM_INT);
        $stmtCursos->bindParam(':mes_final', $mesFinal, PDO::PARAM_INT);
    }

    if (!empty($curso)) {
        $stmtCursos->bindParam(':curso', $curso, PDO::PARAM_STR);
    }

    $stmtCursos->execute();
    $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os dados como JSON
    echo json_encode([
        'livros' => $livros,
        'cursos' => $cursos
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
