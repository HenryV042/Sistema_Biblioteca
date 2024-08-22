<?php
// Conectar ao banco de dados
require_once '../../../dependencies/config.php';

session_start(); // Iniciar sessão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $matricula = $_POST['matricula'] ?? '';
    $numero_registro = $_POST['numero_registro'] ?? '';
    $bibliotecario = $_POST['bibliotecario'] ?? '';

    // Obter informações adicionais para o empréstimo
    try {
        // Iniciar transação
        $conn->beginTransaction();

        // Obter os dados do pedido
        $sqlPedido = "SELECT * FROM pedidos WHERE matricula = :matricula";
        $stmtPedido = $conn->prepare($sqlPedido);
        $stmtPedido->bindParam(':matricula', $matricula);
        $stmtPedido->execute();
        $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            // Obter o ID do aluno
            $sqlAluno = "SELECT id FROM aluno WHERE matricula = :matricula";
            $stmtAluno = $conn->prepare($sqlAluno);
            $stmtAluno->bindParam(':matricula', $matricula);
            $stmtAluno->execute();
            $aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);
            $alunoId = $aluno ? $aluno['id'] : null;

            // Inserir dados na tabela emprestimos
            $sqlEmprestimo = "INSERT INTO emprestimos (
                aluno_id, matricula, titulo_livro, numero_registro, curso, serie, data_emprestimo, status, nome_bibliotecario
            ) VALUES (
                :aluno_id, :matricula, :titulo_livro, :numero_registro, :curso, :serie, NOW(), 'Ativo', :nome_bibliotecario
            )";
            $stmtEmprestimo = $conn->prepare($sqlEmprestimo);
            $stmtEmprestimo->bindParam(':aluno_id', $alunoId);
            $stmtEmprestimo->bindParam(':matricula', $matricula);
            $stmtEmprestimo->bindParam(':titulo_livro', $pedido['titulo_livro']);
            $stmtEmprestimo->bindParam(':numero_registro', $numero_registro);
            $stmtEmprestimo->bindParam(':curso', $pedido['curso']);
            $stmtEmprestimo->bindParam(':serie', $pedido['serie']);
            $stmtEmprestimo->bindParam(':nome_bibliotecario', $bibliotecario);
            $stmtEmprestimo->execute();

            // Deletar o registro da tabela pedidos
            $sqlDeletePedido = "DELETE FROM pedidos WHERE matricula = :matricula";
            $stmtDeletePedido = $conn->prepare($sqlDeletePedido);
            $stmtDeletePedido->bindParam(':matricula', $matricula);
            $stmtDeletePedido->execute();

            // Confirmar transação
            $conn->commit();

            // Definir mensagem de sucesso na sessão
            $_SESSION['message'] = 'Pedido Aceito com Sucesso!';
        } else {
            // Definir mensagem de erro se o pedido não for encontrado
            $_SESSION['message'] = 'Pedido não encontrado!';
        }
    } catch (PDOException $e) {
        // Reverter transação em caso de erro
        $conn->rollBack();
        // Definir mensagem de erro na sessão
        $_SESSION['message'] = 'Erro ao aceitar o pedido';
    }

    // Redirecionar de volta para index.php
    header('Location: ../index.php');
    exit();
} else {
    // Definir mensagem de erro para método de solicitação inválido
    $_SESSION['message'] = 'Método de solicitação inválido.';
    header('Location: ../index.php');
    exit();
}
?>
