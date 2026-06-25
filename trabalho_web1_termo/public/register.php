<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Registrar</title>
  <link rel="stylesheet" href="./login_style.css" />
  <link rel="icon" type="image/png" href="./images/iconTPurple.png">
</head>
<body>
   <div class="back">
    <a href="../index.php">← Voltar</a>
  </div>

  <div class="card">
    <div class="icon">👤</div>
    <h2>Criar Conta</h2>
    <p>Cadastre-se para começar a jogar.</p>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger" style="color: #e06666; margin-bottom: 15px; font-weight: bold; text-align: center;">
          <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form id="registerForm" action="../src/Auth/cadastro_process.php" method="POST">
      
      <label for="username">Nome de usuário</label>
      <input type="text" id="username" name="nome" placeholder="👤 Digite seu nome de usuário" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="📧 Digite seu email" required>

      <label for="password">Senha</label>
      <input type="password" id="password" name="senha" placeholder="🔒 Digite sua senha" required>

      <button type="submit">Criar Conta</button>
    </form>

    <p class="link"><a href="login.php">Já tem uma conta? Faça login</a></p>
  </div>
</body>
</html>