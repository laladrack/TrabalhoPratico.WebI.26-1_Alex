<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}

$nome_liga = trim($_POST['nome_liga'] ?? '');
$palavra_chave = trim($_POST['palavra_chave'] ?? '');

$stmt = $pdo->prepare('SELECT id, palavra_chave FROM ligas WHERE nome = ?');
$stmt->execute([$nome_liga]);
$liga = $stmt->fetch();

if (!$liga || $liga['palavra_chave'] !== $palavra_chave) {
    header('Location: ../../public/dashboard.php?error=Liga não encontrada ou palavra-chave incorreta.');
    exit;
}

try {
    $stmtInserir = $pdo->prepare('INSERT INTO liga_membros (liga_id, usuario_id) VALUES (?, ?)');
    $stmtInserir->execute([$liga['id'], $_SESSION['usuario_id']]);
    header('Location: ../../public/dashboard.php?success=Você entrou na liga!');
} catch (\PDOException $e) {
    header('Location: ../../public/dashboard.php?error=Você já participa desta liga.');
}
exit;