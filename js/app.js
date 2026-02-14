const img = document.getElementById('banner')
const topo = document.getElementById('topo')
const inicial = document.getElementById('inicial')

function ajustarTamanho() {
  let calc = img.offsetHeight - topo.offsetHeight
  inicial.style.minHeight = `${calc}px`
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