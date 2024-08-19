<?php
require_once '../../dependencies/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = $conn->prepare("SELECT * FROM turma WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $turma = $query->fetch(PDO::FETCH_ASSOC);

    if ($turma) {
        echo json_encode([
            'id' => $turma['id'],
            'nome_identificacao' => $turma['nome_identificacao'],
            'curso' => $turma['curso'],
            'ano_inicio' => $turma['ano_inicio'],
            'ano_conclusao' => $turma['ano_conclusao'],
            'serie' => $turma['serie'],
            'atividade' => $turma['atividade']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Turma não encontrada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}
?>
