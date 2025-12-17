// Pega os elementos do menu
const burguer = document.getElementById("burguer");
const menu = document.getElementById("menu-navegacao");

// Abre o menu ao clicar no burguer
burguer.addEventListener("click", () => {
  burguer.classList.toggle("active");
  menu.style.display = "flex";
  menu.classList.remove("fechar-menu");
  menu.classList.add("abrir-menu");
});

// Fecha o menu ao clicar em algum link
menu.addEventListener("click", () => {
  burguer.classList.remove("active");
  menu.classList.remove("abrir-menu");
  menu.classList.add("fechar-menu");

  setTimeout(() => {
    menu.style.display = "none";
  }, 300);
});

// Abre e fecha o modelo
function modelo(resp) {
  const modelo = document.getElementById("modelo");
  if (resp === "abrir") {
    modelo.style.display = "flex";
  } else {
    modelo.style.display = "none";
  }

  const modeloNoticia = document.getElementById("modeloNoticia");
  if (resp === "abrirNoticia") {
    modeloNoticia.style.display = "flex";
  } else {
    modeloNoticia.style.display = "none";
  }
}

// Filtra os serviços por categoria
function filtrar(resp) {
  const lista = document.getElementById("todos-servicos");
  const itens = lista.querySelectorAll("#card-servicos");
  const aviso = document.getElementById("aviso");

  if (aviso != null) {
    lista.removeChild(aviso);
  }

  var disponivel = false;
  for (var i = 0; i < itens.length; i++) {
    const categoria = itens[i].dataset.categoria;

    if (resp === "todos" || categoria === resp) {
      itens[i].style.display = "flex";
      disponivel = true;
    } else {
      itens[i].style.display = "none";
    }
  }

  if (disponivel == false) {
    const aviso = document.createElement("h2");

    aviso.textContent = "Nenhum serviço encontrado";
    aviso.classList.add("aviso");
    aviso.id = "aviso";

    lista.appendChild(aviso);
  }
}

// Pega as informações do perfil
const infoPerfil = document.getElementById("btn-info-perfil");
const info = document.getElementById("info-perfil");

const teste = document.body;

// Abre e fecha as informações do perfil
var infoAberta = false;
infoPerfil.addEventListener("click", () => {
  if (infoAberta == false) {
    info.classList.remove("fechar-info-perfil");
    info.classList.add("abrir-info-perfil");
    infoPerfil.textContent = "Fechar informações do perfil";
  } else {
    info.classList.remove("abrir-info-perfil");
    info.classList.add("fechar-info-perfil");
    infoPerfil.textContent = "Ver informações do perfil";
  }
  infoAberta = !infoAberta;
});