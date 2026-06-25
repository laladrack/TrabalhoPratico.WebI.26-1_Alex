<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("SELECT pontuacao, data_partida FROM partidas WHERE usuario_id = ? ORDER BY data_partida DESC");
$stmt->execute([$_SESSION['usuario_id']]);
$partidas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Partidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container card p-4 shadow-sm" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Meu Histórico de Partidas</h2>
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Voltar ao Painel</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Data / Hora</th>
                    <th>Pontuação Obtida</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($partidas)): ?>
                    <tr><td colspan="2" class="text-center text-muted">Nenhuma partida jogada ainda.</td></tr>
                <?php endif; ?>
                <?php foreach($partidas as $p): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i:s', strtotime($p['data_partida'])) ?></td>
                        <td class="fw-bold text-success"><?= $p['pontuacao'] ?> pts</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>