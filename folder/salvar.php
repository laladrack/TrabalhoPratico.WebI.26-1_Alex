<?php
session_start();
require_once 'conexao.php'; 

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário invalido."]);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);

if (isset($dados['palavra_jogada']) && isset($dados['tentativas']) && isset($dados['ganhou'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $palavra = mb_strtoupper($dados['palavra_jogada'], 'UTF-8');
    $tentativas = (int)$dados['tentativas'];
    $ganhou = filter_var($dados['ganhou'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    try {
        $sql = "INSERT INTO Partidas (id_usuario, palavra_jogada, tentativas, ganhou) 
                VALUES (:id_usuario, :palavra, :tentativas, :ganhou)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':palavra' => $palavra,
            ':tentativas' => $tentativas,
            ':ganhou' => $ganhou
        ]);

        echo json_encode(["sucesso" => true, "mensagem" => "Partida salva com sucesso!"]);
    } catch (PDOException $e) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados incompletos enviados."]);
}
?>
