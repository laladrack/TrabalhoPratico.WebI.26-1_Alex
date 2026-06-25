<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="./login_style.css" />
    <link rel="icon" type="image/png" href="./images/iconTPurple.png">
  </head>
  <body>
    <div class="back">
      <a href="../index.php">← Voltar</a>
    </div>

    <div class="card">
      <div class="icon">👤</div>
      <h2>Bem-vindo!</h2>
      <p>Entre para continuar.</p>

      <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger" style="color: #e06666; margin-bottom: 15px; font-weight: bold;">
          <?= htmlspecialchars($_GET['error']) ?>
        </div>
      <?php endif; ?>
      <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success" style="color: #6aa84f; margin-bottom: 15px; font-weight: bold;">
          <?= htmlspecialchars($_GET['success']) ?>
        </div>
      <?php endif; ?>

      <form id="loginForm" action="../src/Auth/login_process.php" method="POST">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" placeholder="alexprofdeweb@ufpr.br" class="form-content form-control" required>

        <label for="password">Senha</label>
        <input
          type="password"
          id="password"
          name="senha" 
          placeholder="Digite sua senha"
          class="form-control"
          required
        />

        <button type="submit" class="btn btn-primary">Entrar</button>
      </form>

      <p class="link">
        <a href="register.php">Não tem uma conta? Cadastre-se</a>
      </p>
      <small>Seu progresso será salvo de forma segura no servidor.</small>
    </div>
  </body>
</html>