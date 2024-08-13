<?php
require_once '../../dependencies/config.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$numero = $_POST['numero'];
$cpf = $_POST['cpf'];
$matricula = $_POST['matricula'];
$sala_identificacao = $_POST['sala_identificacao'];
$curso = $_POST['curso'];
$serie = $_POST['serie'];

// Obter o id_turma correspondente à sala_identificacao
$sql_turma = "SELECT id FROM turma WHERE nome_identificacao = :sala_identificacao";
$stmt_turma = $conn->prepare($sql_turma);
$stmt_turma->bindParam(':sala_identificacao', $sala_identificacao);
$stmt_turma->execute();
$id_turma = $stmt_turma->fetchColumn();

if ($id_turma !== false) {
    // Atualizar o registro do aluno com o id_turma
    $sql = "UPDATE aluno SET nome_completo = :nome, numero = :numero, cpf = :cpf, matricula = :matricula, sala_identificacao = :sala_identificacao, curso = :curso, serie = :serie, id_turma = :id_turma WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':matricula', $matricula);
    $stmt->bindParam(':sala_identificacao', $sala_identificacao);
    $stmt->bindParam(':curso', $curso);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':id_turma', $id_turma);

    if ($stmt->execute()) {
        echo "<script>alert('Aluno atualizado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar aluno.'); window.location.href='index.php';</script>";
    }
} else {
    echo "<script>alert('Turma não encontrada.'); window.location.href='index.php';</script>";
}
?>
