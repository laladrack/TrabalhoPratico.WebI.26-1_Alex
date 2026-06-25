<?php
session_start();
// bolta pro login seus coisa!

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>TERMO - Jogo</title>
    <link rel="icon" type="image/png" href="./images/iconTPurple.png" />
    <link rel="stylesheet" href="./game_style.css" />
  </head>
  <body>
    <div class="top">
      <div class="back">
        <a href="./dashboard.php">← Voltar ao Painel</a>
      </div>
      <div class="new">
        <a href="game.php">Novo</a>
      </div>
    </div>

    <h1 class="title">TERMO</h1>
    <p class="subtitle">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>
    <p class="attempts">Tentativa 0/6</p>

    <div class="card">
      <div class="grid">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="row">
          <div class="cell"></div>
          <div class="cell"></div>
          <div class="cell"></div>
          <div class="cell"></div>
          <div class="cell"></div>
        </div>
        <?php endfor; ?>
      </div>

      <div class="keyboard">
        <div class="keyboard-row">
          <button class="letter">Q</button>
          <button class="letter">W</button>
          <button class="letter">E</button>
          <button class="letter">R</button>
          <button class="letter">T</button>
          <button class="letter">Y</button>
          <button class="letter">U</button>
          <button class="letter">I</button>
          <button class="letter">O</button>
          <button class="letter">P</button>
        </div>
        <div class="keyboard-row">
          <button class="letter">A</button>
          <button class="letter">S</button>
          <button class="letter">D</button>
          <button class="letter">F</button>
          <button class="letter">G</button>
          <button class="letter">H</button>
          <button class="letter">J</button>
          <button class="letter">K</button>
          <button class="letter">L</button>
        </div>
        <div class="keyboard-row">
          <button class="special">ENTER</button>
          <button class="letter">Z</button>
          <button class="letter">X</button>
          <button class="letter">C</button>
          <button class="letter">V</button>
          <button class="letter">B</button>
          <button class="letter">N</button>
          <button class="letter">M</button>
          <button class="special">⌫</button>
        </div>
      </div>
    </div>

    <script src="js/game.js"></script>
  </body>
</html>