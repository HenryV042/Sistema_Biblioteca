<?php
// addAluno.php

require_once '../../dependencies/config.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar e validar os dados recebidos
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
    $sala_identificacao = filter_input(INPUT_POST, 'sala_identificacao', FILTER_SANITIZE_STRING);
    $curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_STRING);
    $serie = filter_input(INPUT_POST, 'serie', FILTER_SANITIZE_STRING);
    $numero = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING);

    // Validar CPF e Matrícula (aqui você pode adicionar uma função de validação mais robusta, se necessário)
    
    try {
        // Verificar se o CPF ou matrícula já existe
        $sqlCheck = 'SELECT COUNT(*) FROM aluno WHERE cpf = :cpf OR matricula = :matricula';
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindValue(':cpf', $cpf, PDO::PARAM_STR);
        $stmtCheck->bindValue(':matricula', $matricula, PDO::PARAM_STR);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Se já existe um registro com o mesmo CPF ou matrícula, exibir mensagem de erro
            echo "<script>alert('Já existe um aluno com o mesmo CPF ou matrícula.'); window.location.href='index.php';</script>";
        } else {
            // Preparar a consulta SQL para inserção
            $sqlInsert = 'INSERT INTO aluno (numero, nome_completo, cpf, matricula, sala_identificacao, curso, serie) VALUES (:numero, :nome, :cpf, :matricula, :sala_identificacao, :curso, :serie)';
            $stmtInsert = $conn->prepare($sqlInsert);

            // Associar os parâmetros
            $stmtInsert->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmtInsert->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $stmtInsert->bindValue(':matricula', $matricula, PDO::PARAM_STR);
            $stmtInsert->bindValue(':sala_identificacao', $sala_identificacao, PDO::PARAM_STR);
            $stmtInsert->bindValue(':curso', $curso, PDO::PARAM_STR);
            $stmtInsert->bindValue(':serie', $serie, PDO::PARAM_STR);
            $stmtInsert->bindValue(':numero', $numero, PDO::PARAM_STR);

            // Executar a consulta de inserção
            $stmtInsert->execute();

            // Redirecionar para a página principal com uma mensagem de sucesso
            echo "<script>alert('Aluno adicionado com sucesso'); window.location.href='index.php';</script>";
        }

    } catch (PDOException $e) {
        // Exibir mensagem de erro detalhada
        echo "<script>alert('Erro ao adicionar aluno: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='index.php';</script>";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
