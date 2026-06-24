<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$pontuacao = isset($data['pontuacao']) ? (int)$data['pontuacao'] : -1;

if ($pontuacao < 0) {
    echo json_encode(['success' => false, 'message' => 'Pontuação inválida.']);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO partidas (usuario_id, pontuacao) VALUES (?, ?)');
    $stmt->execute([$_SESSION['usuario_id'], $pontuacao]);
    echo json_encode(['success' => true]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco.']);
}