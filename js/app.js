const img = document.getElementById("banner");
const topo = document.getElementById("topo");
const inicial = document.getElementById("inicial");
const user_icon = document.querySelector(".user-icon");

function ajustarTamanho() {
  if (img !== null) {
    let calc = img.offsetHeight - topo.offsetHeight;
    inicial.style.minHeight = `${calc}px`;
  }

  if (user_icon !== null) {
    topo.classList.add("user-cabecalho");

    if (topo.offsetWidth > 570) {
      let cal = topo.offsetHeight / 2;
      user_icon.classList.add("user-icon");
      user_icon.style.marginTop = `${cal}px`;
    } else {
      user_icon.classList.remove("user-icon");
      user_icon.style.marginTop = 0;
    }
  }
}

window.addEventListener("resize", ajustarTamanho);
window.addEventListener("load", ajustarTamanho);

// Filtra os serviços por categoria
function filtrar(categoria) {
  console.log("Filtrando categoria:", categoria);

  $.ajax({
    type: "POST",
    url: "../util/filtro.php",
    data: { resp: categoria },
    success: function (resposta) {
      const container = document.getElementById("todos-servicos");

      container.innerHTML = resposta;

      container.scrollLeft = 0;

      const totalCards = container.querySelectorAll(".card-servico").length;

      if (container.offsetWidth > 600) {
        velocidade = totalCards < 4 ? 0 : 0.7;
      }
    },
  });
}

// Menu topo
const burguer = document.getElementById("burguer");
const nav = document.getElementById("nav-id");

nav.addEventListener("click", () => {
  configMenu();
});

burguer.addEventListener("click", () => {
  configMenu();
});

function configMenu() {
  const isAtivo = nav.classList.contains("ativo");

  if (isAtivo) {
    nav.classList.remove("ativo");
    nav.classList.add("desativado");
  } else {
    nav.classList.remove("desativado");
    nav.classList.add("ativo");
  }

  burguer.classList.toggle("abrir");
}

// Rodapé
const institucional = document.getElementById("institucional");
const atendimento = document.getElementById("atendimento");
const cliente = document.getElementById("cliente");

function abrir_info(opcao) {
  if (opcao == 1) {
    institucional.classList.toggle("expandir");
  } else if (opcao == 2) {
    atendimento.classList.toggle("expandir");
  } else {
    cliente.classList.toggle("expandir");
  }
}

function fechar(opcao) {
  if (opcao == 1) {
    institucional.classList.toggle("expandir");
  } else if (opcao == 2) {
    atendimento.classList.toggle("expandir");
  } else {
    cliente.classList.toggle("expandir");
  }
}

const modal_form = document.getElementById("modal-form");
const abrir = document.getElementById("abrirModal");

// abrir.addEventListener("click", () => {
//   modal_form.style.display = "flex";
// });

// // fechar clicando fora
// modal_form.addEventListener("click", (e) => {
//   if (e.target === modal_form) {
//     modal_form.style.display = "none";
//   }
// });

const botaoGerar = document.getElementById("gerarCodigo");
const codigoDiv = document.getElementById("codigoGerado");

// botaoGerar.addEventListener("click", () => {
//   const codigo = Math.floor(1000 + Math.random() * 9000);
//   codigoDiv.textContent = "Código: " + codigo;
// });

const fechar_modal = document.getElementById("fecharModal");

// fechar_modal.addEventListener("click", () => {
//   modal_form.style.display = "none";
// });





function applyFilter() {
  const cards = document.querySelectorAll(".card");
  let visibleCount = 0;

  cards.forEach(card => {
    const isAvaliado = card.classList.contains("avaliado");

    let show = false;

    if (currentFilter === "todos") show = true;
    if (currentFilter === "pendente") show = !isAvaliado;
    if (currentFilter === "avaliado") show = isAvaliado;

    card.style.display = show ? "block" : "none";
    if (show) visibleCount++;
  });

  document.getElementById("emptyState").style.display =
    visibleCount === 0 ? "block" : "none";
}

document.querySelectorAll(".cardAvaliar").forEach(card => {
  const stars = card.querySelectorAll(".star");
  let rating = 0;

  stars.forEach((star, index) => {
    star.addEventListener("click", () => {
      rating = index + 1;

      stars.forEach((s, i) => {
        s.style.color = i < rating ? "#FFD700" : "#ccc";
      });

      card.setAttribute("data-rating", rating);
    });
  });

  const button = card.querySelector(".submit-btn");

  button.addEventListener("click", () => {
    const ratingValue = card.getAttribute("data-rating");
    const comment = card.querySelector(".comment-area").value;

    if (!ratingValue) {
      alert("Selecione uma nota!");
      return;
    }

    card.classList.add("avaliado");

    button.innerText = "Avaliado ✅";
    button.disabled = true;

    card.querySelector(".star-label").innerText = "Avaliado";

    console.log("Avaliação enviada:", {
      nota: ratingValue,
      comentario: comment
    });

    applyFilter();
  });
});

// Inicialização
applyFilter();

