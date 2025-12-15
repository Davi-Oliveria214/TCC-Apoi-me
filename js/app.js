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
