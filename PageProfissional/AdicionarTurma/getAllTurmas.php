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

// Query to fetch all turmas
$sql = 'SELECT * FROM turma';
$stmt = $conn->prepare($sql);
$stmt->execute();

$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($turmas);
?>
