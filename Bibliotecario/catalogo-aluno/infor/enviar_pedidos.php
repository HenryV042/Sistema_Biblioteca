<?php
require_once '../../../../dependencies/config.php';

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar dados do formulário
    $titulo_livro = isset($_POST['titulo_livro']) ? $_POST['titulo_livro'] : '';
    $matricula = 2882527; // Matrícula do aluno
    $nome_bibliotecario = 'Nome do Bibliotecário'; // Defina o nome do bibliotecário conforme necessário

    try {
        // Conectar ao banco de dados usando PDO
        global $conn; // Acessar a conexão PDO do config.php

        // Verificar se já existe um pedido com a matrícula
        $sqlVerificaPedido = "SELECT COUNT(*) AS total FROM pedidos WHERE matricula = :matricula";
        $stmtVerifica = $conn->prepare($sqlVerificaPedido);
        $stmtVerifica->bindParam(':matricula', $matricula);
        $stmtVerifica->execute();
        $resultado = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

        if ($resultado['total'] > 0) {
            // Se já existe um pedido, redirecionar para uma página de aviso
            header("Location: confirmacao.php?status=pedido_existente&redirect=" . urlencode($_SERVER['HTTP_REFERER']));
            exit;
        }

        // Buscar informações do aluno com base na matrícula
        $sqlAluno = "SELECT id, nome_completo, curso, serie FROM aluno WHERE matricula = :matricula";
        $stmtAluno = $conn->prepare($sqlAluno);
        $stmtAluno->bindParam(':matricula', $matricula);
        $stmtAluno->execute();

        if ($aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC)) {
            $curso = htmlspecialchars($aluno['curso']);
            $serie = htmlspecialchars($aluno['serie']);
        } else {
            // Se o aluno não for encontrado, definir valores padrão e evitar inserção
            $curso = 'Curso não disponível';
            $serie = 'Série não disponível';
        }

        // Inserir dados na tabela pedidos
        $sqlPedido = "INSERT INTO pedidos (matricula, titulo_livro, numero_registro, curso, serie, data_solicitação, nome_bibliotecario) 
                      VALUES (:matricula, :titulo_livro, NULL, :curso, :serie, NOW(), :nome_bibliotecario)";
        
        $stmtPedido = $conn->prepare($sqlPedido);

        $stmtPedido->bindParam(':matricula', $matricula);
        $stmtPedido->bindParam(':titulo_livro', $titulo_livro);
        $stmtPedido->bindParam(':curso', $curso);
        $stmtPedido->bindParam(':serie', $serie);
        $stmtPedido->bindParam(':nome_bibliotecario', $nome_bibliotecario);
        
        $stmtPedido->execute();

        // Redirecionar para a página de confirmação
        header("Location: confirmacao.php?status=success&redirect=" . urlencode($_SERVER['HTTP_REFERER']));
        exit;
    } catch (PDOException $e) {
        echo "Erro ao enviar pedido: " . $e->getMessage();
    }
} else {
    // Se não for um POST, redirecionar ou mostrar uma mensagem de erro
    header("Location: confirmacao.php?status=error&redirect=" . urlencode($_SERVER['HTTP_REFERER']));
    exit;
}
?>
