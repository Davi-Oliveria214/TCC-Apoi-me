const modelo = document.getElementById("modelo");
const abrirBtn = document.getElementById("abrirModelo");
const fecharBtn = document.getElementById("fecharModelo");

abrirBtn.onclick = () => modelo.style.display = "flex";
fecharBtn.onclick = () => modelo.style.display = "none";

window.onclick = (e) => {
  if (e.target === modelo) modelo.style.display = "none";
};

function menu(resposta) {
  const menu_navegacao = document.getElementById("menu-navegacao");

  if (resposta == "abrir") {
    menu_navegacao.style.display = "flex";
  } else if (resposta == "fechar") {
    menu_navegacao.style.display = "none";
  }
}