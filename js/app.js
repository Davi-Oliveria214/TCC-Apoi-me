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

// Menu topo
const burguer = document.getElementById('burguer');
const nav = document.getElementById('nav-id');

burguer.addEventListener('click', () => {
  const isAtivo = nav.classList.contains('ativo');

  if (isAtivo) {
    nav.classList.remove('ativo');
    nav.classList.add('desativado');
  } else {
    nav.classList.remove('desativado');
    nav.classList.add('ativo');
  }

  burguer.classList.toggle('abrir');
});

const institucional = document.getElementById('institucional');
const atendimento = document.getElementById('atendimento');
const cliente = document.getElementById('cliente');

function abrir_info(opcao) {
  if (opcao == 1) {
    institucional.classList.toggle('expandir')
  } else if (opcao == 2) {
    atendimento.classList.toggle('expandir')
  } else {
    cliente.classList.toggle('expandir')
  }
}

function fechar(opcao) {
  if (opcao == 1) {
    institucional.classList.toggle('expandir')
  } else if (opcao == 2) {
    atendimento.classList.toggle('expandir')
  } else {
    cliente.classList.toggle('expandir')
  }
}