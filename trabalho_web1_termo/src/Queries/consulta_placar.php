<?php
function obterPlacarGeralDesdeCriacao($pdo) {
    $sql = "SELECT u.nome, COALESCE(SUM(p.pontuacao), 0) as total_pontos 
            FROM usuarios u
            LEFT JOIN partidas p ON u.id = p.usuario_id
            GROUP BY u.id
            ORDER BY total_pontos DESC";
    return $pdo->query($sql)->fetchAll();
}

function obterPlacarGeralSemanal($pdo) {
    $sql = "SELECT u.nome, COALESCE(SUM(p.pontuacao), 0) as total_pontos 
            FROM usuarios u
            LEFT JOIN partidas p ON u.id = p.usuario_id AND YEARWEEK(p.data_partida, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY u.id
            ORDER BY total_pontos DESC";
    return $pdo->query($sql)->fetchAll();
}

function obterPlacarLigaDesdeCriacao($pdo, $liga_id) {
    $sql = "SELECT u.nome, COALESCE(SUM(p.pontuacao), 0) as total_pontos
            FROM usuarios u
            JOIN liga_membros lm ON u.id = lm.usuario_id
            LEFT JOIN partidas p ON u.id = p.usuario_id
            WHERE lm.liga_id = ?
            GROUP BY u.id
            ORDER BY total_pontos DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$liga_id]);
    return $stmt->fetchAll();
}

function obterPlacarLigaSemanal($pdo, $liga_id) {
    $sql = "SELECT u.nome, COALESCE(SUM(p.pontuacao), 0) as total_pontos
            FROM usuarios u
            JOIN liga_membros lm ON u.id = lm.usuario_id
            LEFT JOIN partidas p ON u.id = p.usuario_id AND YEARWEEK(p.data_partida, 1) = YEARWEEK(CURDATE(), 1)
            WHERE lm.liga_id = ?
            GROUP BY u.id
            ORDER BY total_pontos DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$liga_id]);
    return $stmt->fetchAll();
}

function listarLigasDoUsuario($pdo, $usuario_id) {
    $sql = "SELECT l.id, l.nome FROM ligas l JOIN liga_membros lm ON l.id = lm.liga_id WHERE lm.usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll();
}