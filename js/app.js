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