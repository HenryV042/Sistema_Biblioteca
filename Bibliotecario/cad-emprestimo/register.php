<?php
require_once '../../dependencies/config.php';

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepara e vincula os parâmetros
$stmt = $conn->prepare("INSERT INTO emprestimos (aluno_id, matricula, titulo_livro, numero_registro, curso, serie, data_emprestimo, data_devolucao, data_rascunho, descricao, nome_bibliotecario, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssssss", $aluno_id, $matricula, $titulo_livro, $numero_registro, $curso, $serie, $data_emprestimo, $data_devolucao, $data_rascunho, $descricao, $nome_bibliotecario, $status);

// Define os parâmetros e executa
$aluno_id = $_POST['aluno_id'];
$matricula = $_POST['matricula'];
$titulo_livro = $_POST['titulolivro'];
$numero_registro = $_POST['registrationNumber'];
$curso = $_POST['curso'];
$serie = $_POST['serie'];
$data_emprestimo = $_POST['data'];
$data_devolucao = NULL; // Ajuste conforme necessário
$data_rascunho = NULL;  // Ajuste conforme necessário
$descricao = $_POST['observacao'];
$nome_bibliotecario = "Nome do Bibliotecário"; // Substitua pelo nome real ou por um valor dinâmico
/* $status = "Em andamento"; // Substitua pelo status real */

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
} else {
    echo json_encode(["status" => "success", "message" => "Cadastro realizado com sucesso."]);
}

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();
?>
