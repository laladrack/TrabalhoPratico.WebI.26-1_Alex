<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || empty($senha)) {
        header('Location: /web1/public/cadastro.php?error=Preencha todos os campos corretamente.');
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $email, $senha_hash]);

        $usuario_id = $pdo->lastInsertId();

        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['usuario_nome'] = $nome;

        // Redireciona de forma direta e limpa para o dashboard
        header('Location: /web1/public/dashboard.php');
        exit;
        
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            header('Location: /web1/public/cadastro.php?error=Este e-mail já está cadastrado.');
        } else {
            header('Location: /web1/public/cadastro.php?error=Erro ao cadastrar usuário.');
        }
        exit;
    }
}