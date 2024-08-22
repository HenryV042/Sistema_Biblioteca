<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a tela de login
    header('Location: ../Login/index.php');
    exit;
}
?>