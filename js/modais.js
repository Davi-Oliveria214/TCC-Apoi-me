/* ============================================================
   novo_modais.js — Sistema de modais
   ============================================================ */

const loadModal = document.getElementById('loadModal');

/* ── Abre qualquer modal via AJAX ────────────────────────── */
function abrirModal(tipo, id = '') {
    if (!window.usuarioLogado) {
        window.location.href = './util/setAviso.php';
        return;
    }

    $.ajax({
        url: './includes/modais.php',
        type: 'GET',
        data: { tipo, id },
        beforeSend() {
            loadModal.innerHTML = `
                <div class="modal-overlay" style="display:flex;">
                    <div class="modal-content modal-padrao"
                         style="align-items:center;justify-content:center;min-height:180px;">
                        <div class="modal-spinner"></div>
                    </div>
                </div>`;
        },
        success(resp) {
            loadModal.innerHTML = resp;
            inicializarModal();
        },
        error() {
            loadModal.innerHTML = `
                <div class="modal-overlay" style="display:flex;">
                    <div class="modal-content modal-alerta">
                        <div class="modal-header"><h3>Erro de conexão</h3></div>
                        <div class="modal-body"><p>Não foi possível carregar. Tente novamente.</p></div>
                        <div class="modal-footer">
                            <button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button>
                        </div>
                    </div>
                </div>`;
        }
    });
}

/* ── Fecha o modal ───────────────────────────────────────── */
function fecharModais() {
    const overlay = loadModal?.querySelector('.modal-overlay');
    if (overlay) {
        overlay.style.animation = 'modalSaida .2s ease-in forwards';
        overlay.addEventListener('animationend', () => {
            loadModal.innerHTML = '';
        }, { once: true });
    } else if (loadModal) {
        loadModal.innerHTML = '';
    }
}

/* Fecha ao clicar no overlay */
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) fecharModais();
});

/* Fecha com ESC */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') fecharModais();
});

/* ── Inicializa comportamentos após injeção do HTML ──────── */
function inicializarModal() {

    /* Estrelas de avaliação */
    const stars = loadModal.querySelectorAll('.star');
    const notaInput = loadModal.querySelector('.nota-input');
    const starLabel = loadModal.querySelector('.star-label');

    if (stars.length && notaInput) {
        const labels = ['', 'Péssimo', 'Ruim', 'Regular', 'Bom', 'Excelente'];

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const v = +star.dataset.value;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= v));
            });
            star.addEventListener('mouseleave', () => {
                const atual = +notaInput.value;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= atual));
            });
            star.addEventListener('click', () => {
                const v = +star.dataset.value;
                notaInput.value = v;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= v));
                if (starLabel) starLabel.textContent = labels[v] ?? '';
            });
        });
    }

    /* Contador de caracteres */
    loadModal.querySelectorAll('.comment-area').forEach(ta => {
        const counter = ta.closest('.input-group')?.querySelector('.char-count');
        if (!counter) return;
        const max = ta.maxLength > 0 ? ta.maxLength : 500;
        counter.textContent = `${ta.value.length} / ${max}`;
        ta.addEventListener('input', () => {
            counter.textContent = `${ta.value.length} / ${max}`;
        });
    });

    /* Preview de imagem no modal de serviço */
    const fileInput = loadModal.querySelector('.input-imagem');
    const preview = loadModal.querySelector('#preview');
    if (fileInput && preview) {
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    /* Horários dinâmicos (modal agendar) */
    const dataInput = loadModal.querySelector('#modal-data-input');
    const horaSelect = loadModal.querySelector('#modal-hora-select');
    if (dataInput && horaSelect) {
        dataInput.addEventListener('change', () => {
            const data = dataInput.value;
            const id_servico = dataInput.dataset.servico;
            if (!data) return;

            horaSelect.innerHTML = '<option value="">Carregando...</option>';
            $.ajax({
                url: './includes/modais.php',
                type: 'GET',
                data: { tipo: 'horarios', data, id_servico },
                success: (html) => {
                    horaSelect.innerHTML = html || '<option value="">Nenhum horário disponível</option>';
                },
                error: () => {
                    horaSelect.innerHTML = '<option value="">Erro ao carregar horários</option>';
                }
            });
        });
    }

    /* Verificação de senha no modal editar_senha */
    const senhaInput = loadModal.querySelector('#idSenha');
    const rptInput = loadModal.querySelector('#idRptSenha');
    const textoSenha = loadModal.querySelector('.texto-senha');
    const btnEnviar = loadModal.querySelector('#btnEnviar');

    if (senhaInput && rptInput) {
        /* sobrescreve a função global para este contexto de modal */
        window.verificarSenha = function () {
            const senha = senhaInput.value;
            const rpt = rptInput.value;
            const ok = senha.length >= 8 && senha === rpt;

            if (textoSenha) {
                textoSenha.textContent = senha.length < 8
                    ? 'Mínimo 8 caracteres'
                    : (senha !== rpt ? 'As senhas não coincidem' : '✓ Senhas iguais');
                textoSenha.style.color = ok
                    ? 'var(--musgo-medio)'
                    : 'var(--dourado)';
            }
            if (btnEnviar) btnEnviar.disabled = !ok;
        };
    }
}

/* Preview de avatar (modal editar_img_perfil) */
function previewAvatar(input) {
    const preview = document.getElementById('avatarPreview');
    if (!input.files?.[0]) {
        if (preview) { preview.src = ''; preview.style.display = 'none'; }
        return;
    }
    const arquivo = input.files[0];
    if (!arquivo.type.match(/^image\/(jpeg|jpg|png)$/)) {
        alert('Selecione um arquivo JPG ou PNG.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
    };
    reader.readAsDataURL(arquivo);
}

/* Exclusão de conta — envio do código */
function deletarEnviarCodigo(btn) {
    btn.disabled = true;
    btn.textContent = 'Enviando…';

    const formData = new FormData();
    formData.append('acao', 'enviar_codigo');

    fetch('./controls/conta.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                document.getElementById('del-etapa-1').style.display = 'none';
                const etapa2 = document.getElementById('del-etapa-2');
                etapa2.style.display = 'flex';
                etapa2.classList.add('ativar-load');
                delIniciarCodigo();
                delIniciarTimer();
            } else {
                btn.disabled = false;
                btn.textContent = 'Entendo, enviar código de confirmação';
                alert(data.erro ?? 'Erro ao enviar o código. Tente novamente.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Entendo, enviar código de confirmação';
            alert('Erro de conexão. Tente novamente.');
        });
}

/* Inputs de código  */
function delIniciarCodigo() {
    const boxes = document.querySelectorAll('.del-codigo-box');
    const hidden = document.getElementById('del-codigo-hidden');
    const btn = document.getElementById('btn-confirmar-delecao');

    function sync() {
        const val = [...boxes].map(b => b.value).join('');
        hidden.value = val;
        const ok = val.length === 6 && /^\d{6}$/.test(val);
        btn.disabled = !ok;
        btn.style.opacity = ok ? '1' : '0.4';
    }

    boxes.forEach((box, i) => {
        box.addEventListener('input', e => {
            const v = e.target.value.replace(/\D/g, '');
            box.value = v ? v[0] : '';
            if (v && i < 5) boxes[i + 1].focus();
            sync();
        });
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && i > 0) {
                boxes[i - 1].focus();
                boxes[i - 1].value = '';
                sync();
            }
        });
        box.addEventListener('paste', e => {
            e.preventDefault();
            const txt = (e.clipboardData || window.clipboardData)
                .getData('text').replace(/\D/g, '').slice(0, 6);
            txt.split('').forEach((c, j) => { if (boxes[j]) boxes[j].value = c; });
            boxes[Math.min(txt.length, 5)].focus();
            sync();
        });
    });

    if (boxes[0]) boxes[0].focus();
}

function delIniciarTimer() {
    let s = 15 * 60;
    const txt = document.getElementById('del-timer-txt');
    const btn = document.getElementById('btn-confirmar-delecao');

    const tick = setInterval(() => {
        s--;
        if (s <= 0) {
            clearInterval(tick);
            if (txt) txt.textContent = '00:00';
            if (btn) { btn.disabled = true; btn.style.opacity = '0.4'; }
            return;
        }
        const m = String(Math.floor(s / 60)).padStart(2, '0');
        const sec = String(s % 60).padStart(2, '0');
        if (txt) txt.textContent = `${m}:${sec}`;
    }, 1000);
}