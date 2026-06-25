<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/login.php');
    exit;
}

$nome_liga = trim($_POST['nome_liga']);
$palavra_chave = trim($_POST['palavra_chave']);
$usuario_id = $_SESSION['usuario_id'];

if (empty($nome_liga) || empty($palavra_chave)) {
    header('Location: ../../public/dashboard.php?erro=Campos inválidos.');
    exit;
}

try {
    $stmt_check = $pdo->prepare("SELECT id FROM ligas WHERE nome = :nome");
    $stmt_check->execute([':nome' => $nome_liga]);
    
    if ($stmt_check->fetch()) {
        header('Location: ../../public/dashboard.php?erro=Já existe uma liga com este nome. Escolha outro!');
        exit;
    }

    $pdo->beginTransaction();

    $stmt_liga = $pdo->prepare("INSERT INTO ligas (nome, palavra_chave, criador_id) VALUES (:nome, :chave, :criador)");
    $stmt_liga->execute([
        ':nome' => $nome_liga,
        ':chave' => $palavra_chave,
        ':criador' => $usuario_id
    ]);

    $liga_id = $pdo->lastInsertId();

    $stmt_membro = $pdo->prepare("INSERT INTO liga_usuarios (liga_id, usuario_id) VALUES (:liga_id, :usuario_id)");
    $stmt_membro->execute([
        ':liga_id' => $liga_id,
        ':usuario_id' => $usuario_id
    ]);

    $pdo->commit();
    header('Location: ../../public/dashboard.php?sucesso=Liga criada com sucesso!');
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('Location: ../../public/dashboard.php?erro=Erro ao criar liga no banco de dados.');
    exit;
}