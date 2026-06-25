<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}

$nome_liga = trim(filter_input(INPUT_POST, 'nome_liga', FILTER_SANITIZE_SPECIAL_CHARS));
$palavra_chave = trim($_POST['palavra_chave'] ?? '');

if (empty($nome_liga) || empty($palavra_chave)) {
    header('Location: ../../public/dashboard.php?error=Preencha todos os campos da liga.');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO ligas (nome, palavra_chave, criado_por) VALUES (?, ?, ?)');
    $stmt->execute([$nome_liga, $palavra_chave, $_SESSION['usuario_id']]);
    $liga_id = $pdo->lastInsertId();

    $stmtMembro = $pdo->prepare('INSERT INTO liga_membros (liga_id, usuario_id) VALUES (?, ?)');
    $stmtMembro->execute([$liga_id, $_SESSION['usuario_id']]);

    $pdo->commit();
    header('Location: ../../public/dashboard.php?success=Liga criada com sucesso!');
} catch (\PDOException $e) {
    $pdo->rollBack();
    header('Location: ../../public/dashboard.php?error=Nome da liga já existe ou erro no servidor.');
}
exit;