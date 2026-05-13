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