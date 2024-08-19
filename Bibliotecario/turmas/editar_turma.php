<?php
require_once '../../dependencies/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['turma_id'];
    $nome_identificacao = $_POST['nome_identificacao'];
    $curso = $_POST['curso'];
    $ano_inicio = $_POST['ano_inicio'];
    $ano_conclusao = $_POST['ano_conclusao'];
    $serie = $_POST['serie'];

    // Atualizar os dados da turma no banco de dados
    $query = $conn->prepare("UPDATE turma SET 
                                nome_identificacao = :nome_identificacao, 
                                curso = :curso, 
                                ano_inicio = :ano_inicio, 
                                ano_conclusao = :ano_conclusao, 
                                serie = :serie 
                             WHERE id = :id");
    $query->bindParam(':id', $id);
    $query->bindParam(':nome_identificacao', $nome_identificacao);
    $query->bindParam(':curso', $curso);
    $query->bindParam(':ano_inicio', $ano_inicio);
    $query->bindParam(':ano_conclusao', $ano_conclusao);
    $query->bindParam(':serie', $serie);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Turma atualizada com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar turma.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}
?>