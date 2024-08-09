<?php

header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'eeepma26_biblioteca';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $bookName = $_POST['bookName'] ?? '';
    $author = $_POST['author'] ?? '';
    $cdu = $_POST['cdu'] ?? '';
    $cdd = $_POST['cdd'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $publisher = $_POST['publisher'] ?? '';
    $location = $_POST['location'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $seal = $_POST['seal'] ?? '';
    $acquisitionYear = $_POST['acquisitionYear'] ?? '';
    $registrationNumber = $_POST['registrationNumber'] ?? '';

    // Validate required fields
    $requiredFields = [$bookName, $author, $cdu, $cdd, $origin, $publisher, $location, $genre, $registrationNumber];
    foreach ($requiredFields as $field) {
        if (empty($field)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
            exit();
        }
    }

    // Handle file upload
    $image = null;
    if (isset($_FILES['bookImage']) && $_FILES['bookImage']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['bookImage']['tmp_name']);
    } elseif (isset($_FILES['bookImage']) && $_FILES['bookImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no upload da imagem.']);
        exit();
    }

    function checkRegistrationNumber($conn, $registrationNumber)
    {
        $sql = 'SELECT COUNT(*) FROM livros WHERE numero_registro = :registrationNumber';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':registrationNumber', $registrationNumber);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    try {
        if (checkRegistrationNumber($conn, $registrationNumber)) {
            echo json_encode(['status' => 'error', 'message' => 'Erro: Livro com este número de registro já existe.']);
        } else {
            // Prepare and execute the insert statement
            $sql = 'INSERT INTO livros (titulo_livro, autor, editora, ano_aquisicao, origem, local, genero, cdd, cdu, numero_registro, imagem) VALUES (:bookName, :author, :publisher, :acquisitionYear, :origin, :location, :genre, :cdd, :cdu, :registrationNumber, :image)';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':bookName', $bookName);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':acquisitionYear', $acquisitionYear);
            $stmt->bindParam(':origin', $origin);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':cdd', $cdd);
            $stmt->bindParam(':cdu', $cdu);
            $stmt->bindParam(':registrationNumber', $registrationNumber);
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Livro cadastrado com sucesso!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro ao cadastrar livro: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
