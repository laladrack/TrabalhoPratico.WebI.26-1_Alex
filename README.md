# TERMO 🟩

**TERMO** é um clone em português do Wordle com sistema de autenticação, ligas e placar integrado.

## Visão Geral

- Jogo de adivinhação de palavras em 6 tentativas
- Cadastro, login e sessão de usuário
- Placar global e semanal
- Ligas com acesso por palavra-chave
- Histórico de partidas por usuário

## Tecnologias

- PHP 
- MySQL
- HTML / CSS / JavaScript
- XAMPP para execução local

## Estrutura do Projeto

- `public/` — frontend e páginas públicas
  - `index.php` — tela inicial
  - `login.php` — login de usuários
  - `register.php` — cadastro de novos usuários
  - `dashboard.php` — painel com placar e ligas
  - `game.php` — jogo principal
  - `relatorio.php` — histórico de partidas
  - `js/game.js` — lógica do jogo e requisições de pontuação
  - `js/data/palavras.json` — dicionário de palavras válidas
- `config/db.php` — conexão com o MySQL
- `src/` — backend protegido
  - `Auth/login_process.php` — validação do login
  - `Auth/cadastro_process.php` — cadastro de usuário
  - `Actions/salvar_partida.php` — grava resultados das partidas
  - `Actions/criar_liga.php` — criação de ligas
  - `Actions/entrar_liga.php` — entrada em ligas
  - `Queries/consultas_placar.php` — consultas de ranking
- `database/schema.sql` — esquema para criar tabelas do banco

## Instalação

### 1. Copie para o XAMPP

Coloque a pasta `trabalho_web1_termo` em:

```bash
C:/xampp/htdocs/
```

### 2. Crie o banco de dados

Abra o phpMyAdmin em:

```url
http://localhost/phpmyadmin
```

Importe o arquivo:

```bash
database/schema.sql
```


### 3. Configure a conexão

Edite `config/db.php` se necessário:

```php
$host = 'localhost';
$db   = 'jogo_web1';
$user = 'root';
$pass = ''; // senha padrão do XAMPP
```

### 5. Acesse a aplicação

Navegue para:

```url
http://localhost/trabalho_web1_termo/public/index.php
```

## Uso

1. Clique em **Começar a Jogar**
2. Faça cadastro ou login
3. Entre no dashboard
4. Clique em **JOGAR AGORA**
5. Digite palavras de 5 letras e use **ENTER** para confirmar

## Regras do Jogo

- A palavra tem 5 letras
- Você tem 6 tentativas
- `🟩` letra correta na posição correta
- `🟨` letra correta na posição errada
- `⬜` letra não está na palavra

## Sistema de Pontuação

- 1ª tentativa = 60 pontos
- 2ª tentativa = 50 pontos
- 3ª tentativa = 40 pontos
- 4ª tentativa = 30 pontos
- 5ª tentativa = 20 pontos
- 6ª tentativa = 10 pontos
- derrota = 0 pontos

## Funcionalidades

### Autenticação

- Cadastro com nome, email e senha
- Login por email e senha

### Jogo

- Palavras carregadas de `public/js/data/palavras.json`
- Validação de palavras pelo dicionário
- Feedback visual por cor em cada tentativa
- Salva pontuação ao finalizar a partida

### Histórico e placar

- `dashboard.php` mostra o placar global e semanal
- `relatorio.php` mostra o histórico de partidas do usuário
- Ranking de ligas com seleção por liga

### Ligas

- Criar liga com nome e palavra-chave
- Entrar em liga existente
- Placar específico para a liga selecionada



Trabalho final de WEB 1


