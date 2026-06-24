<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/Queries/consultas_placar.php';

$usuario_id = $_SESSION['usuario_id'];
$ligas = listarLigasDoUsuario($pdo, $usuario_id);

// Verifica se quer ver o placar de uma liga específica
$liga_selecionada = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Jogo de Digitação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</span>
            <div>
                <a href="relatorio.php" class="btn btn-outline-info btn-sm">Meu Histórico</a>
                <a href="../src/Auth/logout.php" class="btn btn-danger btn-sm">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Coluna da Esquerda: O Jogo -->
            <div class="col-md-7 mb-4">
                <div class="card p-4 shadow-sm text-center">
                    <h3>Jogo de Digitação</h3>
                    <p class="text-muted">Digite a palavra abaixo o mais rápido possível e aperte Espaço ou Enter:</p>
                    <h2 id="palavra-alvo" class="text-primary my-4">carregando...</h2>
                    <input type="text" id="campo-digitacao" class="form-control form-control-lg text-center mb-3" placeholder="Comece a digitar aqui..." autocomplete="off">
                    <div class="d-flex justify-content-around">
                        <h5>Pontos: <span id="pontos-jogo">0</span></h5>
                        <h5>Tempo: <span id="tempo-jogo">30</span>s</h5>
                    </div>
                    <button id="btn-recomeçar" class="btn btn-secondary btn-sm mt-3 d-none">Jogar Novamente</button>
                </div>

                <!-- Formulários de Gerenciamento de Ligas -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card p-3 shadow-sm">
                            <h5>Criar Nova Liga</h5>
                            <form action="../src/Actions/criar_liga.php" method="POST">
                                <input type="text" name="nome_liga" class="form-control mb-2" placeholder="Nome da Liga" required>
                                <input type="text" name="palavra_chave" class="form-control mb-2" placeholder="Palavra-Chave de Acesso" required>
                                <button type="submit" class="btn btn-sm btn-primary w-100">Criar</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card p-3 shadow-sm">
                            <h5>Entrar em uma Liga</h5>
                            <form action="../src/Actions/entrar_liga.php" method="POST">
                                <input type="text" name="nome_liga" class="form-control mb-2" placeholder="Nome Exato da Liga" required>
                                <input type="text" name="palavra_chave" class="form-control mb-2" placeholder="Palavra-Chave" required>
                                <button type="submit" class="btn btn-sm btn-success w-100">Entrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita: Os Placares -->
            <div class="col-md-5">
                <div class="card p-3 shadow-sm mb-4">
                    <form method="GET" action="dashboard.php" class="mb-3">
                        <label class="form-label fw-bold">Selecione o Quadro de Pontuação:</label>
                        <select name="liga_id" class="form-select form-select-sm mb-2" onchange="this.form.submit()">
                            <option value="">Placar Global (Todos os usuários)</option>
                            <?php foreach ($ligas as $l): ?>
                                <option value="<?= $l['id'] ?>" <?= $liga_selecionada == $l['id'] ? 'selected' : '' ?>>
                                    Liga: <?= htmlspecialchars($l['name'] ?? $l['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>

                    <?php
                    if ($liga_selecionada) {
                        $placarGeral = obterPlacarLigaDesdeCriacao($pdo, $liga_selecionada);
                        $placarSemanal = obterPlacarLigaSemanal($pdo, $liga_selecionada);
                        echo "<h6>Amostra: Classificação da Liga</h6>";
                    } else {
                        $placarGeral = obterPlacarGeralDesdeCriacao($pdo);
                        $placarSemanal = obterPlacarGeralSemanal($pdo);
                        echo "<h6>Amostra: Classificação Global</h6>";
                    }
                    ?>

                    <ul class="nav nav-tabs mt-2" id="tabsPlacar" roletablist>
                        <li class="nav-item">
                            <button class="nav-link active btn-sm" id="geral-tab" data-bs-toggle="tab" data-bs-target="#geral-panel" type="button">Desde a Criação</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn-sm" id="semanal-tab" data-bs-toggle="tab" data-bs-target="#semanal-panel" type="button">Semanal</button>
                        </li>
                    </ul>
                    <div class="tab-content p-2 border border-top-0 bg-white">
                        <div class="tab-pane fade show active" id="geral-panel">
                            <table class="table table-sm table-striped mb-0">
                                <thead><tr><th>Pos</th><th>Jogador</th><th>Pontos</th></tr></thead>
                                <tbody>
                                    <?php $pos=1; foreach($placarGeral as $p): ?>
                                        <tr><td><?=$pos++?>º</td><td><?=htmlspecialchars($p['nome'])?></td><td><?=$p['total_pontos']?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="semanal-panel">
                            <table class="table table-sm table-striped mb-0">
                                <thead><tr><th>Pos</th><th>Jogador</th><th>Pontos</th></tr></thead>
                                <tbody>
                                    <?php $pos=1; foreach($placarSemanal as $p): ?>
                                        <tr><td><?=$pos++?>º</td><td><?=htmlspecialchars($p['nome'])?></td><td><?=$p['total_pontos']?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap e Lógica do Jogo -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/game.js"></script>
</body>
</html>