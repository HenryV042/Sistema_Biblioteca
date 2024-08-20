<?php
// login.php

// Database connection
$host = 'localhost';
$dbname = 'eeepma26_biblioteca';
$user = 'eeepma26_biblioteca_master';
$password = 'biblioteca@info2024';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
