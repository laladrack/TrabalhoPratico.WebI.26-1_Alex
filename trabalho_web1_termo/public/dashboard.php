<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/Queries/consultas_placar.php';

$usuario_id = $_SESSION['usuario_id'];
$ligas = listarLigasDoUsuario($pdo, $usuario_id);
$liga_selecionada = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Central do Termo</title>
    <link rel="stylesheet" href="./dashboard_style.css">
</head>
<body>

    <nav class="navbar">
        <div class="navbar-container">
            <span class="navbar-brand">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</span>
            <div class="navbar-links">
                <a href="relatorio.php" class="btn-history">Meu Histórico</a>
                <a href="../src/Auth/logout.php" class="btn-logout">Sair</a>
            </div>
        </div>
    </nav>
    
    <?php if (isset($_GET['erro'])): ?>
    <div style="background: #fce8e6; color: #cc0000; border: 1px solid #f8cbcb; padding: 12px 20px; width: 90%; max-width: 1100px; margin: 0 auto 20px auto; border-radius: 8px; font-weight: bold; font-size: 0.95rem; box-sizing: border-box;">
        ❌ <?= htmlspecialchars($_GET['erro']) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['sucesso'])): ?>
    <div style="background: #edf7ed; color: #1e4620; border: 1px solid #c2e7c4; padding: 12px 20px; width: 90%; max-width: 1100px; margin: 0 auto 20px auto; border-radius: 8px; font-weight: bold; font-size: 0.95rem; box-sizing: border-box;">
        ✅ <?= htmlspecialchars($_GET['sucesso']) ?>
    </div>
<?php endif; ?>
    <div class="main-container">
        
        <div class="left-column">
            
            <div class="game-card" style="text-align: center; padding: 35px 25px;">
                <h3>Pronto para o Desafio?</h3>
                <p class="text-muted">Mostre sua agilidade e conquiste o topo do quadro de pontuação!</p>
                
                <a href="game.php" class="btn-submit btn-purple" style="display: block; text-decoration: none; font-size: 1.2rem; padding: 15px; margin-top: 15px; text-align: center;">
                    🎮 JOGAR AGORA
                </a>
            </div>

            <div class="liga-row">
                <div class="form-box">
                    <h5>Criar Nova Liga</h5>
                    <form action="../src/Actions/criar_liga.php" method="POST">
                        <input type="text" name="nome_liga" placeholder="Nome da Liga" required>
                        <input type="text" name="palavra_chave" placeholder="Chave de Acesso" required>
                        <button type="submit" class="btn-purple">Criar</button>
                    </form>
                </div>
                
                <div class="form-box">
                    <h5>Entrar em uma Liga</h5>
                    <form action="../src/Actions/entrar_liga.php" method="POST">
                        <input type="text" name="nome_liga" placeholder="Nome Exato da Liga" required>
                        <input type="text" name="palavra_chave" placeholder="Palavra-Chave" required>
                        <button type="submit" class="btn-green">Entrar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="side-card">
                <div class="select-container">
                    <form method="GET" action="dashboard.php" id="form-liga">
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;">Selecione o Quadro:</label>
                        <select name="liga_id" onchange="document.getElementById('form-liga').submit()">
                            <option value="">Placar Global (Todos os usuários)</option>
                            <?php foreach ($ligas as $l): ?>
                                <option value="<?= $l['id'] ?>" <?= $liga_selecionada == $l['id'] ? 'selected' : '' ?>>
                                    Liga: <?= htmlspecialchars($l['name'] ?? $l['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="leaderboard-container">
                    <?php
                    if ($liga_selecionada) {
                        $placarGeral = obterPlacarLigaDesdeCriacao($pdo, $liga_selecionada);
                        $placarSemanal = obterPlacarLigaSemanal($pdo, $liga_selecionada);
                        echo "<h6>Classificação da Liga</h6>";
                    } else {
                        $placarGeral = obterPlacarGeralDesdeCriacao($pdo);
                        $placarSemanal = obterPlacarGeralSemanal($pdo);
                        echo "<h6>Classificação Global</h6>";
                    }
                    ?>

                    <div class="nav-tabs">
                        <button id="btn-geral" class="active" onclick="alternarTab('geral')">Desde a Criação</button>
                        <button id="btn-semanal" onclick="alternarTab('semanal')">Semanal</button>
                    </div>

                    <div id="panel-geral" class="tab-pane active">
                        <table class="table">
                            <thead><tr><th>Pos</th><th>Jogador</th><th>Pontos</th></tr></thead>
                            <tbody>
                                <?php $pos=1; if(!empty($placarGeral)): foreach($placarGeral as $p): ?>
                                    <tr><td><?=$pos++?>º</td><td><?=htmlspecialchars($p['nome'])?></td><td><?=$p['total_pontos']?></td></tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="3" style="text-align:center;" class="text-muted">Nenhuma pontuação registrada.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="panel-semanal" class="tab-pane">
                        <table class="table">
                            <thead><tr><th>Pos</th><th>Jogador</th><th>Pontos</th></tr></thead>
                            <tbody>
                                <?php $pos=1; if(!empty($placarSemanal)): foreach($placarSemanal as $p): ?>
                                    <tr><td><?=$pos++?>º</td><td><?=htmlspecialchars($p['nome'])?></td><td><?=$p['total_pontos']?></td></tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="3" style="text-align:center;" class="text-muted">Nenhuma pontuação nesta semana.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function alternarTab(tab) {
            document.getElementById('panel-geral').classList.remove('active');
            document.getElementById('panel-semanal').classList.remove('active');
            document.getElementById('btn-geral').classList.remove('active');
            document.getElementById('btn-semanal').classList.remove('active');

            if(tab === 'geral') {
                document.getElementById('panel-geral').classList.add('active');
                document.getElementById('btn-geral').classList.add('active');
            } else {
                document.getElementById('panel-semanal').classList.add('active');
                document.getElementById('btn-semanal').classList.add('active');
            }
        }
    </script>
</body>
</html>