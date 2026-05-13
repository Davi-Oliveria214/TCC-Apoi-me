function ativarFiltro(el) {
    document.querySelectorAll('.filtro-item').forEach(f => f.classList.remove('ativo'));
    el.classList.add('ativo');
}

const burguer = document.getElementById('burguer');
const nav = document.querySelector('.fundo-topo');

burguer.addEventListener('click', () => {
    nav.classList.toggle('ativo');
    burguer.classList.toggle('abrir');
});

function tipoChange(radio) {
    const cnpj = document.getElementById('campoCnpj');
    const cnpjInput = document.getElementById('cnpjId');
    const hidden = document.getElementById('tipo_usuario_hidden');
    hidden.value = radio.value;
    if (radio.value === 'sindico') {
        cnpj.classList.add('visivel');
        cnpjInput.required = true;
    } else {
        cnpj.classList.remove('visivel');
        cnpjInput.required = false;
    }
}

function toggleSenha(id, btn) {
    const input = document.getElementById(id);
    const olho = document.getElementById('olho-' + id);
    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    olho.src = !visible ? './icon/visibility.png' : './icon/visibility_lock.png';
}

function checarForca(senha) {
    const segs = [document.getElementById('f1'), document.getElementById('f2'), document.getElementById('f3'), document.getElementById('f4')];
    const txt = document.getElementById('forca-txt');
    segs.forEach(s => s.style.background = 'rgba(176,124,32,0.15)');

    let forca = 0;
    if (senha.length >= 8) forca++;
    if (/[A-Z]/.test(senha)) forca++;
    if (/[0-9]/.test(senha)) forca++;
    if (/[^A-Za-z0-9]/.test(senha)) forca++;

    const cores = ['#c0392b', '#e67e22', '#f1c40f', '#1a7a4a'];
    const labels = ['Muito fraca', 'Fraca', 'Boa', 'Forte'];
    for (let i = 0; i < forca; i++) segs[i].style.background = cores[forca - 1];
    txt.textContent = senha.length ? labels[forca - 1] || '' : '';
    txt.style.color = forca > 0 ? cores[forca - 1] : '#9a9a9a';
}

function verificarSenha() {
    const s1 = document.getElementById('idSenha').value;
    const s2 = document.getElementById('idRptSenha').value;
    const span = document.getElementById('senha-match');
    if (!s2) {
        span.textContent = '';
        return;
    }
    if (s1 === s2) {
        span.textContent = '✓ Senhas coincidem';
        span.style.color = '#1a7a4a';
    } else {
        span.textContent = '✗ Senhas não coincidem';
        span.style.color = '#c0392b';
    }
}

function selecionarTipo(btn) {
    document.querySelectorAll('.tipo-pill').forEach(p => p.classList.remove('ativo'));
    btn.classList.add('ativo');
    document.getElementById('tipo_feedback').value = btn.textContent;
}

function atualizarContador() {
    const t = document.getElementById('comentarios');
    document.getElementById('cont').textContent = t.value.length;
}

function toggleFaq(item) {
    const aberto = item.classList.contains('aberto');
    document.querySelectorAll('.faq-item').forEach(f => f.classList.remove('aberto'));
    if (!aberto) item.classList.add('aberto');
}

function enviarForm(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-enviar');
    btn.textContent = 'Enviando…';
    btn.disabled = true;

    setTimeout(() => {
        document.getElementById('form-area').style.display = 'none';
        document.getElementById('sucesso').style.display = 'block';
    }, 1200);
}

function filtro(btn, local, item) {
    $('.js-filtro').removeClass('ativo');
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