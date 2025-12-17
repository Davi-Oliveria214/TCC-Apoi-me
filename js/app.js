const burguer = document.getElementById("burguer");
const menu = document.getElementById("menu-navegacao");

burguer.addEventListener("click", () => {
  burguer.classList.toggle("active");
  menu.style.display = "flex";
  menu.classList.remove("fechar-menu");
  menu.classList.add("abrir-menu");
});

menu.addEventListener("click", () => {
  burguer.classList.remove("active");
  menu.classList.remove("abrir-menu");
  menu.classList.add("fechar-menu");

  setTimeout(() => {
    menu.style.display = "none";
  }, 300);
});

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

function filtrar(resp) {
  const lista = document.getElementById("todos-servicos");
  const itens = lista.querySelectorAll("#card-servicos");
  const aviso = document.getElementById("aviso");

  if (aviso != null) {
    lista.removeChild(aviso);
  }

  var teste = false;
  for (var i = 0; i < itens.length; i++) {
    const categoria = itens[i].dataset.categoria;

    if (resp === "todos" || categoria === resp) {
      itens[i].style.display = "";
      teste = true;
    } else {
      itens[i].style.display = "none";
    }
  }

  if (teste == false) {
    const aviso = document.createElement("h2");

    aviso.textContent = "Nenhum serviço encontrado";
    aviso.classList.add("aviso");
    aviso.id = "aviso";

   
    lista.appendChild(aviso);
  }
}
