# TERMO 🟩

Clone do Wordle em português com sistema de autenticação, ligas entre jogadores e placar global/semanal.

---

## Tecnologias

- **Front-end:** HTML, CSS, JavaScript
- **Back-end:** PHP 
- **Banco de dados:** MySQL
- **Servidor local:** XAMPP

---

## Instalação

### Pré-requisitos

- XAMPP (ou equivalente) com Apache e MySQL ativos
- PHP 8.0 ou superior

### Passo a passo

**1. Clonar ou copiar o projeto**

Coloque a pasta `trabalho_web1_termo` dentro de `htdocs` (XAMPP) ou `www` (WAMP):

```
C:/xampp/htdocs/trabalho_web1_termo/
```

**2. Criar o banco de dados**

Acesse o phpMyAdmin (`http://localhost/phpmyadmin`) e importe o arquivo:

```
database/criacao_sql.sql
```

Isso cria o banco `jogo_web1` com todas as tabelas necessárias.

**3. Configurar a conexão**

Abra `config/db.php` e ajuste as credenciais se necessário:

```php
$host = 'localhost';
$db   = 'jogo_web1';
$user = 'root';
$pass = '';        // senha do MySQL (vazio no XAMPP padrão)
```

**4. Popular o dicionário de palavras**

Acesse no navegador:

```
http://localhost/trabalho_web1_termo/public/povoar.php
```

Isso insere as palavras do `palavras.json` na tabela `Dicionario_Palavras`. Execute apenas uma vez.

**5. Acessar o projeto**

```
http://localhost/trabalho_web1_termo/public/index.php
```

---

## Como jogar

1. Crie uma conta na tela de cadastro
2. Faça login
3. Clique em **Jogar Agora** no dashboard
4. Tente adivinhar a palavra de 5 letras em até **6 tentativas**

### Feedback por cores

| Cor | Significado |
|---|---|
| 🟩 Verde | Letra correta na posição correta |
| 🟨 Amarelo | Letra correta na posição errada |
| ⬜ Cinza | Letra não está na palavra |

---

## Sistema de pontuação

A pontuação de cada partida é calculada como:

```
pontos = 7 - número de tentativas  (somente em caso de vitória)
```

Exemplos: acertar na 1ª tentativa = 6 pts, na 6ª tentativa = 1 pt, derrota = 0 pts.

---

## Ligas

No dashboard é possível:

- **Criar uma liga** — define nome e palavra-chave de acesso
- **Entrar em uma liga** — informa o nome exato e a palavra-chave
- **Ver o placar da liga** — ranking geral (desde a criação) e semanal (últimos 7 dias)

---

## Segurança

- Senhas armazenadas com **bcrypt** (`password_hash` / `password_verify`)
- Proteção contra **SQL injection** via PDO com prepared statements
- Proteção contra **session fixation** com `session_regenerate_id()`
- Dados de usuário exibidos com `htmlspecialchars()` para prevenir **XSS**
- Arquivos de back-end (`src/`, `config/`) fora da pasta pública

---

## Observações

- O arquivo `povoar.php` deve ser executado apenas uma vez e pode ser removido após popular o banco
- Em ambiente de produção, configure a variável `$pass` em `config/db.php` com uma senha forte e nunca exponha esse arquivo publicamente
- O jogo funciona offline para o usuário; a partida só é salva no banco se o jogador estiver autenticado

---

## Autores

Projeto desenvolvido para a disciplina de Desenvolvimento Web — UFPR.
