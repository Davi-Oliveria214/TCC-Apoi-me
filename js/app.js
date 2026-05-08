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

// Tela de carregar
document.addEventListener('click', function (e) {
  const link = e.target.closest('a');
  if (!link) return;

  const href = link.getAttribute('href');

  if (
    href &&
    !href.startsWith('#') &&
    !href.startsWith('javascript') &&
    link.target !== '_blank'
  ) {
    load(true);
  }
});

document.addEventListener('submit', function (e) {
  const form = e.target;

  if (form.classList.contains('ativar-load')) {
    load(true);
  }
});

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

  const container = estrela.closest('.star-rating');
  const estrelas = container.querySelectorAll('.star');
  const inputOculto = container.querySelector('.nota-input');
  const label = container.querySelector('.star-label');

  const valor = estrela.getAttribute('data-value');
  inputOculto.value = valor;

  label.textContent = `Nota: ${valor} / 5`;

  estrelas.forEach(s => {
    if (parseInt(s.getAttribute('data-value')) <= parseInt(valor)) {
      s.classList.add('active');
    } else {
      s.classList.remove('active');
    }
  });
});

document.addEventListener('input', function (e) {
  if (e.target.matches('.comment-area')) {
    const textarea = e.target;
    const contador = textarea.parentElement.querySelector('.char-count');

    if (contador) {
      contador.textContent = `${textarea.value.length} / 500`;
    }
  }
});

// Imagem em tempo real
document.addEventListener('change', function (e) {
  if (e.target.classList.contains('input-imagem')) {
    const input = e.target;
    const file = input.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (event) {
      const container = input.closest('.input-group');
      const preview = container.querySelector('.preview-imagem');

      if (preview) {
        preview.src = event.target.result;
      }
    };

    reader.readAsDataURL(file);
  }
});

// Barra de pesquisa
function pesquisa(pagina, valor) {
  $.ajax({
    url: "./includes/pesquisa.php",
    type: "GET",
    data: { pagina: pagina, pesquisa: valor },
    success: function (resp) {
      const resultado = document.querySelector(".local-filtro");
      if (resultado) {
        resultado.innerHTML = resp;
      }
      gridFiltro()
    }
  });
}

function filtro(btn, local, item) {
  $('.filtro-btn').removeClass('ativo');
  $(btn).addClass('ativo');

  $.ajax({
    url: "./util/filtro.php",
    type: "POST",
    data: { type: local, item: item },
    success: function (resp) {
      const filtro_local = document.querySelector('.local-filtro');
      if (filtro_local) {
        filtro_local.innerHTML = resp;
      }
      gridFiltro()
    },
    error: function () {
      console.error("Erro ao carregar o filtro.");
    }
  });
}

function gridFiltro() {
  const container = document.querySelector('.local-filtro');

  if (container) {
    const temAviso = container.querySelector('.aviso-vazio');

    if (temAviso) {
      container.style.display = 'flex';
    } else {
      container.style.display = 'grid';
    }
  }
}

// Validar senha
function verificarSenha() {
  const nome = document.getElementById('idNome')
  const email = document.getElementById('idEmail')
  const senha = document.getElementById('idSenha')
  const rptSenha = document.getElementById('idRptSenha')
  const inputSenha = document.getElementById('idSenha')

  $.ajax({
    url: "./controls/verificarSenha.php",
    type: "POST",
    dataType: "json",
    data: { pass: senha.value, rptSenha: rptSenha.value, nome: nome.value, email: email.value },
    success: function (resp) {
      const campoErro = document.querySelector('.texto-senha');

      if (campoErro) {
        campoErro.textContent = resp.msg;
      }

      if (resp.pronto === false) {
        inputSenha.setCustomValidity(resp.msg);
      } else {
        inputSenha.setCustomValidity("");
      }
    }
  });
}

function toggleSenha(idInput, imgElement) {
  const input = document.getElementById(idInput);

  if (input.type === 'password') {
    input.type = 'text';
    imgElement.src = './icon/visibility_lock.png';
    imgElement.alt = 'Ocultar senha';
  } else {
    input.type = 'password';
    imgElement.src = './icon/visibility.png';
    imgElement.alt = 'Mostrar senha';
  }
}