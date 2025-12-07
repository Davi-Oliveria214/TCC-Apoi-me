const modelo = document.getElementById("modelo");
const abrirBtn = document.getElementById("abrirModelo");
const fecharBtn = document.getElementById("fecharModelo");

abrirBtn.onclick = () => (modelo.style.display = "flex");
fecharBtn.onclick = () => (modelo.style.display = "none");

window.onclick = (e) => {
  if (e.target === modelo) modelo.style.display = "none";
};

function menu(resposta) {
  const menu_navegacao = document.getElementById("menu-navegacao");
  const menu = document.getElementById("teste");

  if (resposta === "abrir") {
    menu_navegacao.style.display = "flex";
    menu_navegacao.classList.remove("fechar-menu");
    menu_navegacao.classList.add("abrir-menu");
  } else if (resposta === "fechar") {
    menu_navegacao.classList.remove("abrir-menu");
    menu_navegacao.classList.add("fechar-menu");

    setTimeout(() => {
      menu_navegacao.style.display = "none";
    }, 300);
  }
}