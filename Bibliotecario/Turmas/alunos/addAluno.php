<?php
// addAluno.php

require_once '../dependencies/config.php';

// Verificar se o formulário foi enviado e os dados necessários estão presentes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $matricula = isset($_POST['matricula']) ? $_POST['matricula'] : '';
    $curso = isset($_POST['curso']) ? $_POST['curso'] : '';
    $serie = isset($_POST['serie']) ? $_POST['serie'] : '';

    // Verificar se já existe um aluno com o mesmo CPF ou matrícula
    $check_query = $conn->prepare('
        SELECT COUNT(*) FROM aluno WHERE cpf = :cpf OR matricula = :matricula
    ');
    $check_query->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $check_query->bindValue(':matricula', $matricula, PDO::PARAM_INT);
    $check_query->execute();

    if ($check_query->fetchColumn() > 0) {
        // CPF ou matrícula já existem
        echo 'Já existe um aluno com o mesmo CPF ou matrícula.';
        exit;
    }

    // Inserir novo aluno na tabela aluno
    $insert_query = $conn->prepare('
        INSERT INTO aluno (numero, matricula, cpf, nome_completo, sala_identificacao, curso, serie)
        VALUES (:numero, :matricula, :cpf, :nome_completo, :sala_identificacao, :curso, :serie)
    ');

    // O número pode ser gerado automaticamente ou atribuído
    $numero = 0; // Defina ou gere um número apropriado

    $insert_query->bindValue(':numero', $numero, PDO::PARAM_INT);
    $insert_query->bindValue(':matricula', $matricula, PDO::PARAM_INT);
    $insert_query->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $insert_query->bindValue(':nome_completo', $nome, PDO::PARAM_STR);
    $insert_query->bindValue(':sala_identificacao', $curso, PDO::PARAM_STR); // Ajuste se necessário
    $insert_query->bindValue(':curso', $curso, PDO::PARAM_STR);
    $insert_query->bindValue(':serie', $serie, PDO::PARAM_STR);

    if ($insert_query->execute()) {
        echo 'Aluno adicionado com sucesso.';
    } else {
        echo 'Erro ao adicionar aluno.';
    }
} else {
    echo 'Método de solicitação inválido.';
}
?>
