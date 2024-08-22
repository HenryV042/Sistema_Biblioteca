<?php
require_once '../../dependencies/config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Erro desconhecido'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se todos os campos foram enviados
    if (empty($_POST['nome_identificacao']) || empty($_POST['ano_inicio']) || empty($_POST['ano_conclusao']) || empty($_POST['serie']) || empty($_POST['curso'])) {
        $response['message'] = "Todos os campos são obrigatórios.";
        echo json_encode($response);
        exit;
    }

    // Sanitizar entradas
    $nome_identificacao = htmlspecialchars(trim($_POST['nome_identificacao']));
    $ano_inicio = filter_var($_POST['ano_inicio'], FILTER_SANITIZE_NUMBER_INT);
    $ano_conclusao = filter_var($_POST['ano_conclusao'], FILTER_SANITIZE_NUMBER_INT);
    $serie = htmlspecialchars(trim($_POST['serie']));
    $curso = htmlspecialchars(trim($_POST['curso']));

    // Validar tipos de dados
    if (!is_numeric($ano_inicio) || !is_numeric($ano_conclusao)) {
        $response['message'] = "Ano de início e ano de conclusão devem ser números válidos.";
        echo json_encode($response);
        exit;
    }

    if ($ano_conclusao < $ano_inicio) {
        $response['message'] = "O ano de conclusão não pode ser anterior ao ano de início.";
        echo json_encode($response);
        exit;
    }

    // Inserir no banco de dados
    try {
        $stmt = $conn->prepare("INSERT INTO turma (nome_identificacao, curso, ano_inicio, ano_conclusao, serie, atividade) VALUES (:nome_identificacao, :curso, :ano_inicio, :ano_conclusao, :serie, 1)");
        $stmt->bindParam(':nome_identificacao', $nome_identificacao);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':ano_inicio', $ano_inicio);
        $stmt->bindParam(':ano_conclusao', $ano_conclusao);
        $stmt->bindParam(':serie', $serie);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Turma adicionada com sucesso!';
        } else {
            $response['message'] = 'Erro ao adicionar turma.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Erro ao adicionar turma: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
