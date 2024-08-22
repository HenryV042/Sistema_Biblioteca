<?php
$anoAtual = date("Y");
header('Content-Type: application/json');
require '../../dependencies/config.php'; // Inclua o arquivo de conexão com o banco de dados

$curso = isset($_GET['curso']) ? $_GET['curso'] : '';
$turma = isset($_GET['turma']) ? $_GET['turma'] : '';

// Verifique se os parâmetros foram fornecidos
if (empty($curso) || empty($turma)) {
    echo json_encode([]);
    exit();
}

// Debug: Exibir os parâmetros recebidos
// echo json_encode(['curso' => $curso, 'turma' => $turma, 'anoAtual' => $anoAtual]);
// exit();

try {
    if ($turma != '4') {
        $query = "SELECT id, nome_completo, matricula FROM aluno WHERE curso = :curso AND serie = :serie";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':serie', $turma);
    } else {
        $query = "SELECT id, nome_completo, matricula FROM aluno WHERE serie = :serie";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':serie', $turma);
    }

    $stmt->execute();
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($alunos);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>