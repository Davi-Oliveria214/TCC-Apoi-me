/* ── Burger menu (lateral direito) ───────────────────────── */
const burguer = document.getElementById('burguer');
const nav = document.querySelector('.fundo-topo');

let overlay = document.querySelector('.menu-overlay');
if (!overlay) {
    overlay = document.createElement('div');
    overlay.classList.add('menu-overlay');
    document.body.appendChild(overlay);
}

function abrirMenu() {
    nav.classList.add('ativo');
    burguer.classList.add('abrir');
    overlay.classList.add('ativo');
    document.body.style.overflow = 'hidden';
}

function fecharMenu() {
    nav.classList.remove('ativo');
    burguer.classList.remove('abrir');
    overlay.classList.remove('ativo');
    document.body.style.overflow = '';
}

if (burguer && nav) {
    burguer.addEventListener('click', () => {
        nav.classList.contains('ativo') ? fecharMenu() : abrirMenu();
    });

    overlay.addEventListener('click', fecharMenu);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fecharMenu();
    });

    nav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', fecharMenu);
    });
}

/* ── Scroll do topo ──────────────────────────────────────── */
const topo = document.getElementById('topo');
if (topo) {
    window.addEventListener('scroll', () => {
        topo.toggleAttribute('topo-fixo', window.scrollY > 80);
    });
}

/* ── Ajuste de banner ────────────────────────────────────── */
function ajustarTamanho() {
    const img = document.getElementById('banner');
    const inicial = document.getElementById('inicial');
    const userIcon = document.querySelector('.user-icon');

    if (img && inicial) {
        inicial.style.minHeight = `${img.offsetHeight - (topo?.offsetHeight ?? 0)}px`;
    }

    if (userIcon && topo) {
        topo.classList.add('user-cabecalho');
        if (topo.offsetWidth > 570) {
            userIcon.style.marginTop = `${topo.offsetHeight / 2}px`;
        } else {
            userIcon.style.marginTop = '0px';
        }
    }
}

window.addEventListener('resize', ajustarTamanho);
window.addEventListener('load', ajustarTamanho);

/* ── Loading em submits com .ativar-load ─────────────────── */
document.addEventListener('submit', (e) => {
    if (e.target.classList.contains('ativar-load')) load(true);
});

function load(abrir) {
    const body = document.getElementById('body-load');
    if (!body) return;

    if (abrir) {
        $.ajax({
            url: './util/load.php',
            success: (res) => { body.innerHTML = res; $(body).fadeIn(); }
        });
    } else {
        $(body).fadeOut(400, () => { body.innerHTML = ''; });
    }
}

/* ── Toggle de visibilidade de senha ─────────────────────── */
function toggleSenha(inputId, btn) {
    const input = document.getElementById(inputId);
    const olho = document.getElementById('olho-' + inputId);
    if (!input) return;

    const visivel = input.type === 'password';
    input.type = visivel ? 'text' : 'password';

    if (olho) {
        olho.src = visivel
            ? './icon/visibility_lock.png'
            : './icon/visibility.png';
    }
}

/* ── Cadastro: tipo de usuário ───────────────────────────── */
function tipoChange(radio) {
    const cnpj = document.getElementById('campoCnpj');
    const cnpjInput = document.getElementById('cnpjId');
    const hidden = document.getElementById('tipo_usuario_hidden');

    if (hidden) hidden.value = radio.value;

    if (radio.value === 'sindico') {
        cnpj?.classList.add('visivel');
        if (cnpjInput) cnpjInput.required = true;
    } else {
        cnpj?.classList.remove('visivel');
        if (cnpjInput) cnpjInput.required = false;
    }
}

/* ── Força da senha (cadastro / recuperação) ─────────────── */
function checarForca(senha) {
    const segs = ['f1', 'f2', 'f3', 'f4'].map(id => document.getElementById(id));
    const txt = document.getElementById('forca-txt');
    if (!segs[0]) return;

    segs.forEach(s => s && (s.style.background = 'rgba(176,124,32,0.15)'));

    let forca = 0;
    if (senha.length >= 8) forca++;
    if (/[A-Z]/.test(senha)) forca++;
    if (/[0-9]/.test(senha)) forca++;
    if (/[^A-Za-z0-9]/.test(senha)) forca++;

    const cores = ['#c0392b', '#e67e22', '#f1c40f', '#1a7a4a'];
    const labels = ['Muito fraca', 'Fraca', 'Boa', 'Forte'];

    for (let i = 0; i < forca; i++) {
        if (segs[i]) segs[i].style.background = cores[forca - 1];
    }
    if (txt) {
        txt.textContent = senha.length ? (labels[forca - 1] ?? '') : '';
        txt.style.color = forca > 0 ? cores[forca - 1] : '#9a9a9a';
    }
}

/* ── Verificar coincidência de senha (cadastro) ──────────── */
function verificarSenha() {
    const s1 = document.getElementById('idSenha')?.value ?? '';
    const s2 = document.getElementById('idRptSenha')?.value ?? '';
    const span = document.getElementById('senha-match');
    const btn = document.getElementById('btnEnviar');
    if (!span) return;

    const ok = s1.length >= 8 && s1 === s2;

    if (!s2) {
        span.textContent = '';
    } else if (ok) {
        span.textContent = '✓ Senhas coincidem';
        span.style.color = '#1a7a4a';
    } else {
        span.textContent = s1.length < 8 ? 'Mínimo 8 caracteres' : '✗ Senhas não coincidem';
        span.style.color = '#c0392b';
    }

    if (btn) btn.disabled = !ok;
}

/* ── Força da senha na tela verificar_acesso ─────────────── */
function vaForca(senha) {
    const segs = ['vaf1', 'vaf2', 'vaf3', 'vaf4'].map(id => document.getElementById(id));
    const txt = document.getElementById('va-forca-txt');
    if (!segs[0]) return;

    segs.forEach(s => s && (s.style.background = 'rgba(176,124,32,0.15)'));

    let f = 0;
    if (senha.length >= 8) f++;
    if (/[A-Z]/.test(senha)) f++;
    if (/[0-9]/.test(senha)) f++;
    if (/[^A-Za-z0-9]/.test(senha)) f++;

    const cores = ['#c0392b', '#e67e22', '#f1c40f', '#1a7a4a'];
    const labels = ['Muito fraca', 'Fraca', 'Boa', 'Forte'];

    for (let i = 0; i < f; i++) {
        if (segs[i]) segs[i].style.background = cores[f - 1];
    }
    if (txt) {
        txt.textContent = senha.length ? (labels[f - 1] ?? '') : '';
        txt.style.color = f > 0 ? cores[f - 1] : '#9a9a9a';
    }
}

/* ── Verificar senha na tela verificar_acesso ────────────── */
function vaVerificarSenha() {
    const s1 = document.getElementById('va-senha')?.value ?? '';
    const s2 = document.getElementById('va-rpt-senha')?.value ?? '';
    const txt = document.getElementById('va-match-txt');
    const btn = document.getElementById('va-btn-salvar');

    const ok = s1.length >= 8 && s1 === s2;

    if (txt) {
        if (!s2) { txt.textContent = ''; }
        else if (ok) {
            txt.textContent = '✓ Senhas coincidem';
            txt.style.color = '#1a7a4a';
        } else {
            txt.textContent = s1.length < 8 ? 'Mínimo 8 caracteres' : '✗ Senhas não coincidem';
            txt.style.color = '#c0392b';
        }
    }
    if (btn) btn.disabled = !ok;
}

/* ── Tipo de feedback / contato ──────────────────────────── */
function selecionarTipo(btn) {
    document.querySelectorAll('.tipo-pill').forEach(p => p.classList.remove('ativo'));
    btn.classList.add('ativo');
    const inp = document.getElementById('tipo_feedback');
    if (inp) inp.value = btn.textContent;
}

function atualizarContador() {
    const t = document.getElementById('comentarios');
    const c = document.getElementById('cont');
    if (t && c) c.textContent = t.value.length;
}

/* ── FAQ ─────────────────────────────────────────────────── */
function toggleFaq(item) {
    const aberto = item.classList.contains('aberto');
    document.querySelectorAll('.faq-item').forEach(f => f.classList.remove('aberto'));
    if (!aberto) item.classList.add('aberto');
}

/* ── Filtro de cards ─────────────────────────────────────── */
function filtro(btn, local, item) {
    $(btn).closest('ul').find('.js-filtro').removeClass('ativo');
    $(btn).addClass('ativo');

    $.ajax({
        url: './util/filtro.php',
        type: 'POST',
        data: { type: local, item },
        success: (resp) => {
            const sel = local === 'contratos'
                ? '.local-filtro-contrato'
                : '.local-filtro';
            const el = document.querySelector(sel);
            if (el) { el.innerHTML = resp; gridFiltro(); }
        }
    });
}

function pesquisa(pagina, valor) {
    $.ajax({
        url: './includes/pesquisa.php',
        type: 'GET',
        data: { pagina, pesquisa: valor },
        success: (resp) => {
            const el = document.querySelector('.local-filtro');
            if (el) { el.innerHTML = resp; gridFiltro(); }
        }
    });
}

function gridFiltro() {
    const container = document.querySelector('.local-filtro');
    if (!container) return;
    container.style.display = (container.querySelector('.aviso-vazio, .sv-vazio-grid, .an-vazio, .an-contratos-vazio')) ? 'flex' : 'grid';
}

/* ── Preview de imagem genérica (fora de modal) ──────────── */
document.addEventListener('change', (e) => {
    if (!e.target.classList.contains('input-imagem')) return;
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        const preview = e.target.closest('.input-group')?.querySelector('.preview-imagem');
        if (preview) preview.src = ev.target.result;
    };
    reader.readAsDataURL(file);
});

function scrollCarrossel(direcao) {
    const track = document.querySelector('.carrossel-track');
    const scrollAmount = track.clientWidth * 0.8;
    track.scrollBy({
        left: direcao * scrollAmount,
        behavior: 'smooth'
    });
}

function validarHorarios() {
    const inicio = document.getElementById('hora_inicio');
    const fim = document.getElementById('hora_fim');
    const btn = document.getElementById('btnSubmitServico');

    if (inicio.value) {
        fim.min = inicio.value;
    }

    if (inicio.value && fim.value && fim.value <= inicio.value) {
        alert('O horário de término deve ser posterior ao horário de início.');
        fim.value = '';
        btn.disabled = true;
    } else {
        btn.disabled = false;
    }
}

function mascaraMoeda(campo) {
    let valor = campo.value.replace(/\D/g, '');

    if (valor === '') {
        valor = '0';
    }

    let valorFloat = (parseFloat(valor) / 100).toFixed(2);
    document.getElementById('preco_oculto').value = valorFloat;

    valor = (parseFloat(valor) / 100).toFixed(2) + '';
    valor = valor.replace('.', ',');
    valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    campo.value = valor;
}