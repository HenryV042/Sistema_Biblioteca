<?php
// Include the database connection file
require_once '../../dependencies/config.php';

date_default_timezone_set('America/Sao_Paulo');

// Set the content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['Name'] ?? '';
    $matricula = $_POST['matricula'] ?? '';
    $titulo_livro = $_POST['titulolivro'] ?? '';
    $numero_registro = $_POST['registrationNumber'] ?? '';
    $curso = $_POST['curso'] ?? '';
    $serie = $_POST['turma'] ?? '';
    $data_emprestimo = $_POST['data'] ?? '';
    $nome_bibliotecario = $_POST['nomebibliotecario'] ?? '';
    $descricao = $_POST['observacao'] ?? '';

    try {
        // Create a new PDO connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validate course and series
        $stmt_turma = $conn->prepare("SELECT id FROM turma WHERE curso = :curso AND serie = :serie");
        $stmt_turma->bindParam(':curso', $curso);
        $stmt_turma->bindParam(':serie', $serie);
        $stmt_turma->execute();

        if ($stmt_turma->rowCount() > 0) {
            $id_turma = $stmt_turma->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Curso ou série inválidos']);
            exit;
        }

        // Find the student by matricula
        $stmt_aluno = $conn->prepare("SELECT id FROM aluno WHERE matricula = :matricula AND id_turma = :id_turma");
        $stmt_aluno->bindParam(':matricula', $matricula);
        $stmt_aluno->bindParam(':id_turma', $id_turma);
        $stmt_aluno->execute();
        
        if ($stmt_aluno->rowCount() > 0) {
            $aluno_id = $stmt_aluno->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Aluno não encontrado']);
            exit;
        }

        // Check if the book exists
        $stmt_livro = $conn->prepare("SELECT * FROM livros WHERE titulo_livro = :titulo_livro AND numero_registro = :numero_registro");
        $stmt_livro->bindParam(':titulo_livro', $titulo_livro);
        $stmt_livro->bindParam(':numero_registro', $numero_registro);
        $stmt_livro->execute();
        if ($stmt_livro->rowCount() === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Livro não encontrado']);
            exit;
        }

        // Insert the loan record into the database
        $stmt = $conn->prepare("INSERT INTO emprestimos (aluno_id, matricula, titulo_livro, numero_registro, curso, serie, data_emprestimo, nome_bibliotecario, descricao, status)
                                VALUES (:aluno_id, :matricula, :titulo_livro, :numero_registro, :curso, :serie, :data_emprestimo, :nome_bibliotecario, :descricao, 'pendente')");
        $stmt->bindParam(':aluno_id', $aluno_id);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':titulo_livro', $titulo_livro);
        $stmt->bindParam(':numero_registro', $numero_registro);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':serie', $serie);
        $stmt->bindParam(':data_emprestimo', $data_emprestimo);
        $stmt->bindParam(':nome_bibliotecario', $nome_bibliotecario);
        $stmt->bindParam(':descricao', $descricao);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao realizar o cadastro']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}
?>
