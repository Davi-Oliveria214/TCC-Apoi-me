const burguer = document.getElementById("burguer");
const menu_navegacao = document.getElementById("menu-navegacao");

burguer.addEventListener("click", () => {
  burguer.classList.toggle("active");
  menu_navegacao.style.display = "flex";
  menu_navegacao.classList.remove("fechar-menu");
  menu_navegacao.classList.add("abrir-menu");
});

menu_navegacao.addEventListener("click", () => {
  burguer.classList.remove("active");
  menu_navegacao.classList.remove("abrir-menu");
  menu_navegacao.classList.add("fechar-menu");

  setTimeout(() => {
    menu_navegacao.style.display = "none";
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
