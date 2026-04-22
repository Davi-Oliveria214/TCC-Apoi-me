const img = document.getElementById("banner");
const topo = document.getElementById("topo");
const inicial = document.getElementById("inicial");
const user_icon = document.querySelector(".user-icon");

window.addEventListener('scroll', function () {
  topo.toggleAttribute('topo-fixo', window.scrollY > 80)
});

function ajustarTamanho() {
  if (img !== null) {
    let calc = img.offsetHeight - topo.offsetHeight;
    inicial.style.minHeight = `${calc}px`;
  }

  if (user_icon !== null) {
    topo.classList.add("user-cabecalho");

    if (topo.offsetWidth > 570) {
      let cal = topo.offsetHeight / 2;
      user_icon.classList.add("user-icon");
      user_icon.style.marginTop = `${cal}px`;
    } else {
      user_icon.classList.remove("user-icon");
      user_icon.style.marginTop = 0;
    }
  }
}

window.addEventListener("resize", ajustarTamanho);
window.addEventListener("load", ajustarTamanho);

// Filtra os serviços por categoria
function filtrar(categoria) {
  $.ajax({
    type: "POST",
    url: "../util/filtro.php",
    data: { resp: categoria },
    success: function (resposta) {
      const container = document.getElementById("todos-servicos");

      container.innerHTML = resposta;

      container.scrollLeft = 0;

      const totalCards = container.querySelectorAll(".card-servico").length;

      if (container.offsetWidth > 600) {
        velocidade = totalCards < 4 ? 0 : 0.7;
      }
    },
  });
}

window.document.getElementById('ativar-load')?.addEventListener('submit', function () {
  load(true)
})

// Menu topo
const burguer = document.getElementById("burguer");
const nav = document.getElementById("nav-id");

nav.addEventListener("click", () => {
  configMenu();
});

burguer.addEventListener("click", () => {
  configMenu();
});

function configMenu() {
  const isAtivo = nav.classList.contains("ativo");

  if (isAtivo) {
    nav.classList.remove("ativo");
    nav.classList.add("desativado");
  } else {
    nav.classList.remove("desativado");
    nav.classList.add("ativo");
  }

  burguer.classList.toggle("abrir");
}

function load(abrir) {
  const body = document.getElementById('body-load')

  if (abrir) {
    $.ajax({
      url: "./util/load.php",
      success: function (res) {
        body.innerHTML = res;
        $(body).fadeIn();
      }
    })
  } else {
    $(body).fadeOut(400, function () {
      body.innerHTML = ''
    })
  }
}

document.addEventListener('click', function (e) {
  const estrela = e.target.closest('.star');

  if (!estrela) return;

  const container = estrela.closest('.star-row');
  const estrelas = container.querySelectorAll('.star');
  const inputOculto = container.querySelector('.nota-input');
  const label = container.querySelector('.star-label');

  const valor = estrela.getAttribute('data-value');
  inputOculto.value = valor;

  label.textContent = `Nota: ${valor} / 5`;

  estrelas.forEach(s => {
    if (s.getAttribute('data-value') <= valor) {
      s.classList.add('active');
    } else {
      s.classList.remove('active');
    }
  });
});

document.addEventListener('input', function (e) {
  if (e.target.matches('#area-comentario')) {
    const textarea = e.target;
    const contador = document.getElementById('char-count');

    contador.textContent = `${textarea.value.length} / 500`;
  }
});

document.addEventListener('submit', function (e) {
  const form = e.target;

  if (form.matches('.ativar-load')) {
    load(true)
  }
})

document.addEventListener('change', function (e) {
  if (e.target.classList.contains('input-imagem')) {
    const input = e.target;
    const file = input.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (event) {
      const container = input.closest('.upload-wrapper') || input.parentElement;
      const preview = container.querySelector('.preview-imagem');

      if (preview) {
        preview.src = event.target.result;
      }
    };

    reader.readAsDataURL(file);
  }
})