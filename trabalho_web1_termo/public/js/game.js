// TERMO - game.js
const TOTAL_TENTATIVAS = 6;
const TOTAL_LETRAS = 5;

let palavraSecreta = "";
let tentativaAtual = 0;
let letraAtual = 0;
let jogoAtivo = true;
let listaPalavras = [];

async function carregarPalavras() {
  if (listaPalavras.length > 0) return listaPalavras; // usa cache

  const response = await fetch("../data/palavras.json");
  const data = await response.json();
  const validas = data.palavras
    .map((p) => normalizarTexto(p))
    .filter((p) => p.length === TOTAL_LETRAS);

  listaPalavras = validas;
  return listaPalavras;
}

function normalizarTexto(texto) {
  return texto
    .toUpperCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^A-Z]/g, "");
}

async function novaPalavra() {
  const palavras = await carregarPalavras();
  const idx = Math.floor(Math.random() * palavras.length);
  return palavras[idx];
}
 
// pegar cel
function getCelula(linha, col) {
  return document.querySelectorAll(".row")[linha].querySelectorAll(".cell")[col];
}
 
function atualizarTentativa() {
  document.querySelector(".attempts").textContent =
    `Tentativa ${tentativaAtual}/6`;
}

// pegar letras
function inserirLetra(letra) {
  if (!jogoAtivo || letraAtual >= TOTAL_LETRAS) return;
  const celula = getCelula(tentativaAtual, letraAtual);
  celula.textContent = letra;
  celula.classList.add("preenchida");
  letraAtual++;
}

function apagarLetra() {
  if (!jogoAtivo || letraAtual <= 0) return;
  letraAtual--;
  const celula = getCelula(tentativaAtual, letraAtual);
  celula.textContent = "";
  celula.classList.remove("preenchida");
}

async function confirmarTentativa() {
  if (!jogoAtivo || letraAtual < TOTAL_LETRAS) {
    if (letraAtual < TOTAL_LETRAS) mostrarMensagem("Não é essa palavra não!");
    return;
  }

  const linha = document.querySelectorAll(".row")[tentativaAtual];
  const celulas = linha.querySelectorAll(".cell");
  const tentativa = Array.from(celulas)
    .map((c) => c.textContent)
    .join("");

  //  Validação: checar se a palavra está na lista. 
  // o problema é que a lista não tem todas palavras, isso pode ser meio chato pro jogador, por favor professor não utilize palavras dificeis.
  const palavras = await carregarPalavras();
  const validas = palavras.map((p) => normalizarTexto(p));
  if (!validas.includes(tentativa)) {
    mostrarMensagem("Palavra inválida!");
    return; // não prossegue com avaliação
  }

  const resultado = avaliarTentativa(tentativa, palavraSecreta);

  resultado.forEach((status, i) => {
    setTimeout(() => {
      celulas[i].classList.add("revelando", status);
      atualizarTecla(tentativa[i], status);
    }, i * 120);
  });

  tentativaAtual++;
  letraAtual = 0;
  atualizarTentativa();

  const ganhou = resultado.every((r) => r === "certo");

  setTimeout(
    async () => {
      if (ganhou) {
        jogoAtivo = false;
        mostrarMensagem("🎉 Parabéns! Acertou!");
      } else if (tentativaAtual >= TOTAL_TENTATIVAS) {
        jogoAtivo = false;
        mostrarMensagem(`Fim de jogo! A palavra era: ${palavraSecreta}`);
      }
    },
    TOTAL_LETRAS * 120 + 200,
  );
}

// validar tentativa e marcar cores
function avaliarTentativa(tentativa, secreta) {
  const resultado = Array(TOTAL_LETRAS).fill("errado");
  const letrasSecreta = secreta.split("");
  const letrasTentativa = tentativa.split("");
  const usado = Array(TOTAL_LETRAS).fill(false);

  // Primeira passagem: letras certas no lugar certo (verde)
  letrasTentativa.forEach((letra, i) => {
    if (letra === letrasSecreta[i]) {
      resultado[i] = "certo";
      usado[i] = true;
    }
  });

  // Segunda passagem: letras certas no lugar errado (amarelo)
  letrasTentativa.forEach((letra, i) => {
    if (resultado[i] === "certo") return;
    const j = letrasSecreta.findIndex((l, idx) => l === letra && !usado[idx]);
    if (j !== -1) {
      resultado[i] = "presente";
      usado[j] = true;
    }
  });

  return resultado;
}

//atualizar teclado virtual
function atualizarTecla(letra, status) {
  const prioridade = { certo: 3, presente: 2, errado: 1 };
  const botoes = document.querySelectorAll(
    ".keyboard .letter, .keyboard .special",
  );

  botoes.forEach((btn) => {
    if (btn.textContent.trim() === letra) {
      const atual = btn.dataset.status;
      if (!atual || prioridade[status] > prioridade[atual]) {
        btn.dataset.status = status;
        btn.className = btn.classList.contains("special")
          ? `special ${status}`
          : `letter ${status}`;
      }
    }
  });
}

// devolver mensagens pro usuário
function mostrarMensagem(texto) {
  let msg = document.getElementById("mensagem");
  if (!msg) {
    msg = document.createElement("div");
    msg.id = "mensagem";
    document.querySelector(".card").insertAdjacentElement("afterend", msg);
  }
  msg.textContent = texto;
  msg.classList.remove("fade-out");
  msg.classList.add("visivel");
 
  const permanente =
    texto.includes("palavra era") ||
    texto.includes("Parabéns") ||
    texto.includes("acertou");
 
  if (!permanente) {
    setTimeout(() => msg.classList.add("fade-out"), 1800);
  }
}

// reset
async  function resetarJogo() {
  tentativaAtual = 0;
  letraAtual = 0;
  jogoAtivo = true;

  palavraSecreta = await novaPalavra();
  console.log("Nova palavra (debug):", palavraSecreta);

  document.querySelectorAll(".cell").forEach((c) => {
    c.textContent = "";
    c.className = "cell";
  });

  document.querySelectorAll(".letter, .special").forEach((btn) => {
    const base = btn.classList.contains("special") ? "special" : "letter";
    btn.className = base;
    delete btn.dataset.status;
  });

  const msg = document.getElementById("mensagem");
  if (msg) msg.remove();

  atualizarTentativa();
}

async function iniciar() {
  palavraSecreta = await novaPalavra();
  console.log("Palavra (debug):", palavraSecreta);
  atualizarTentativa();
 
  // Teclado virtual
  document.querySelectorAll(".keyboard .letter").forEach((btn) => {
    btn.addEventListener("click", () => inserirLetra(btn.textContent.trim()));
  });
 
  document.querySelectorAll(".keyboard .special").forEach((btn) => {
    if (btn.textContent.includes("⌫")) {
      btn.addEventListener("click", apagarLetra);
    } else if (btn.textContent.includes("ENTER")) {
      btn.addEventListener("click", confirmarTentativa);
    }
  });
  // botao resetar
  document.querySelector(".new a").addEventListener("click", async (e) => {
    e.preventDefault();
    await resetarJogo();
  });
  //teclado físico
  document.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      confirmarTentativa();
    } else if (e.key === "Backspace") {
      apagarLetra();
    } else if (/^[a-zA-ZÀ-ú]$/.test(e.key)) {
      inserirLetra(normalizarTexto(e.key));
    }
  });
}

iniciar();
