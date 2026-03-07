const img = document.getElementById('banner')
const topo = document.getElementById('topo')
const inicial = document.getElementById('inicial')
const user_icon = document.querySelector('.user-icon');

function ajustarTamanho() {
  if (img !== null) {
    let calc = img.offsetHeight - topo.offsetHeight
    inicial.style.minHeight = `${calc}px`
  }

  if (user_icon !== null) {
    topo.classList.add('user-cabecalho');

    if (topo.offsetWidth > 570) {
      let cal = topo.offsetHeight / 2;
      user_icon.classList.add('user-icon')
      user_icon.style.marginTop = `${cal}px`;
    } else {
      user_icon.classList.remove('user-icon')
      user_icon.style.marginTop = 0;
    }
  }
}

window.addEventListener('resize', ajustarTamanho)
window.addEventListener('load', ajustarTamanho)

// Filtra os serviços por categoria
function filtrar(categoria) {
  console.log(categoria);
  $.ajax({
    type: "POST",
    url: "./util/filtro.php",
    data: { resp: categoria },
    success: function (resposta) {
      document.getElementById("todos-servicos").innerHTML = resposta;
    }
  });
}

const burguer = document.getElementById('burguer');
const nav = document.getElementById('nav-id');

burguer.addEventListener('click', () => {
  nav.classList.toggle('ativo');
  burguer.classList.toggle('abrir');
});