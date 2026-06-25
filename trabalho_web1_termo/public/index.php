<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Wordle</title>
    <link rel="stylesheet" href="index_style.css" />
    <script src="js/main.js" defer></script>
  </head>
  <body>
    <div class="card">
      <h1 class="title">TERMO</h1>
      <p class="subtitle">Adivinhe a palavra em 6 tentativas</p>

      <div class="features">
        <div class="feature">
          <span class="icon">🧠</span>
          <p>
            <strong>Desafie sua mente</strong>
          </p>
          <p> Use a lógica para descobrir a
            palavra do dia
          </p>
        </div>
        <div class="feature">
          <span class="icon">⚡</span>
          <p>
            <strong>6 tentativas</strong>
          </p>
          <p>
            Cada palpite revela pistas sobre a
            palavra
          </p>
        </div>
        <div class="feature">
          <span class="icon">🏆</span>
          <p>
            <strong>Compete diariamente</strong>
          </p>
          <p>
            Uma nova palavra a cada dia
          </p>
        </div>
      </div>

      <a href="./login.php" class="btn">Começar a Jogar</a>

      <div class="how-to-play">
        <h2>Como jogar:</h2>
        <div class="rules">
          <div class="rule">
            <div class="letter green">A</div>
            <div class="rule explanation">Letra correta na posição correta</div>
          </div>
          <div class="rule">
            <div class="letter orange">B</div>
            <div class="rule explanation">Letra correta na posição errada</div>
          </div>
          <div class="rule">
            <div class="letter gray">C</div>
            <div class="rule explanation">Letra não está na palavra</div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
