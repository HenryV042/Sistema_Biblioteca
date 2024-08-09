
<?php
// login.php

// Database connection
$host = 'localhost';
$dbname = 'eeepma26_biblioteca';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
// Inclua o arquivo de conexão

// Verifica se o ID foi passado via GET e se é um número
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Prepara a consulta SQL para buscar informações do aluno
        $stmt = $conn->prepare("
            SELECT a.id, a.numero, a.matricula, a.cpf, a.nome_completo, a.sala_identificacao, 
                   a.curso, a.serie, t.nome_identificacao AS sala_nome, t.curso AS turma_curso
            FROM aluno a
            JOIN turma t ON a.sala_identificacao = t.nome_identificacao AND a.curso = t.curso
            WHERE a.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Obtém o resultado
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($aluno) {
            // Exibe as informações do aluno
            echo "<h1>Informações do Aluno</h1>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($aluno['id']) . "</p>";
            echo "<p><strong>Número:</strong> " . htmlspecialchars($aluno['numero']) . "</p>";
            echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($aluno['matricula']) . "</p>";
            echo "<p><strong>CPF:</strong> " . htmlspecialchars($aluno['cpf']) . "</p>";
            echo "<p><strong>Nome Completo:</strong> " . htmlspecialchars($aluno['nome_completo']) . "</p>";
            echo "<p><strong>Sala Identificação:</strong> " . htmlspecialchars($aluno['sala_identificacao']) . " (Turma: " . htmlspecialchars($aluno['sala_nome']) . ")</p>";
            echo "<p><strong>Curso:</strong> " . htmlspecialchars($aluno['curso']) . "</p>";
            echo "<p><strong>Série:</strong> " . htmlspecialchars($aluno['serie']) . "</p>";
        } else {
            echo "<p>Aluno não encontrado.</p>";
        }
    } catch (PDOException $e) {
        error_log("Error fetching student information: " . $e->getMessage()); // Log the error message
        echo "<p>Ocorreu um erro ao buscar as informações do aluno.</p>";
    }
} else {
    echo "<p>ID do aluno inválido.</p>";
}
?>
