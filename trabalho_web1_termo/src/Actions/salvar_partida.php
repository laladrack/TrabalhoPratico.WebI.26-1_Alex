<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (isset($dados['pontos'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $pontos = (int)$dados['pontos'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO partidas (usuario_id, pontuacao, data_partida) VALUES (:usuario_id, :pontos, NOW())");
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':pontos' => $pontos
        ]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Pontuação gravada com sucesso!']);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados.']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados ausentes.']);
    exit;
}