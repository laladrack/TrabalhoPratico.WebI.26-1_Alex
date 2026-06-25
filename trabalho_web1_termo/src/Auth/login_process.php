<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$email || empty($senha)) {
        header('Location: ../../public/login.php?error=Por favor, preencha todos os campos.');
        exit;
    }

    $stmt = $pdo->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        
        header('Location: ../../public/dashboard.php');
        exit;
    } else {
        header('Location: ../../public/login.php?error=E-mail ou senha incorretos.');
        exit;
    }
}