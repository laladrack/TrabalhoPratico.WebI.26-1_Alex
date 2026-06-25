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
    $stmt_liga = $pdo->prepare("SELECT id, palavra_chave FROM ligas WHERE nome = :nome");
    $stmt_liga->execute([':nome' => $nome_liga]);
    $liga = $stmt_liga->fetch(PDO::FETCH_ASSOC);

    if (!$liga) {
        header('Location: ../../public/dashboard.php?erro=Essa liga não existe! Verifique o nome digitado.');
        exit;
    }

    if ($liga['palavra_chave'] !== $palavra_chave) {
        header('Location: ../../public/dashboard.php?erro=Chave de acesso incorreta para esta liga.');
        exit;
    }

    $liga_id = $liga['id'];

    $stmt_membro = $pdo->prepare("SELECT id FROM liga_usuarios WHERE liga_id = :liga_id AND usuario_id = :usuario_id");
    $stmt_membro->execute([':liga_id' => $liga_id, ':usuario_id' => $usuario_id]);
    
    if ($stmt_membro->fetch()) {
        header('Location: ../../public/dashboard.php?erro=Você já faz parte desta liga!');
        exit;
    }

    $stmt_inserir = $pdo->prepare("INSERT INTO liga_usuarios (liga_id, usuario_id) VALUES (:liga_id, :usuario_id)");
    $stmt_inserir->execute([
        ':liga_id' => $liga_id,
        ':usuario_id' => $usuario_id
    ]);

    header("Location: ../../public/dashboard.php?liga_id=$liga_id&sucesso=Você entrou na liga!");
    exit;

} catch (PDOException $e) {
    header('Location: ../../public/dashboard.php?erro=Erro ao processar solicitação.');
    exit;
}