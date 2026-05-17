const loadModal = document.getElementById('loadModal');

function abrirModal(tipo, id = '') {
    if (!window.usuarioLogado) {
        window.location.href = '/util/setAviso.php';
        return;
    }

    $.ajax({
        url: './includes/modais.php',
        type: 'GET',
        data: { tipo, id },
        beforeSend() {
            loadModal.innerHTML = `
                <div class="modal-overlay" style="display:flex;">
                    <div class="modal-content modal-padrao" style="align-items:center; justify-content:center; min-height:180px;">
                        <div class="modal-spinner"></div>
                    </div>
                </div>`;
        },
        success(resp) {
            loadModal.innerHTML = resp;
            inicializarModal();
        }
    });
}

function fecharModais() {
    const overlay = loadModal.querySelector('.modal-overlay');
    if (overlay) {
        overlay.style.animation = 'modalSaida .2s ease-in forwards';
        overlay.addEventListener('animationend', () => {
            loadModal.innerHTML = '';
        }, { once: true });
    } else {
        loadModal.innerHTML = '';
    }
}

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) fecharModais();
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') fecharModais();
});

function inicializarModal() {
    const stars = loadModal.querySelectorAll('.star');
    const notaInput = loadModal.querySelector('.nota-input');
    const starLabel = loadModal.querySelector('.star-label');

    if (stars.length && notaInput) {
        const labels = ['', 'Péssimo', 'Ruim', 'Regular', 'Bom', 'Excelente'];

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const val = +star.dataset.value;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
            });

            star.addEventListener('mouseleave', () => {
                const atual = +notaInput.value;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= atual));
            });

            star.addEventListener('click', () => {
                const val = +star.dataset.value;
                notaInput.value = val;
                stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
                if (starLabel) starLabel.textContent = labels[val] ?? '';
            });
        });
    }

    const textareas = loadModal.querySelectorAll('.comment-area');

    textareas.forEach(ta => {
        const counter = ta.closest('.input-group')?.querySelector('.char-count');
        if (!counter) return;

        const max = ta.maxLength > 0 ? ta.maxLength : 500;
        counter.textContent = `${ta.value.length} / ${max}`;

        ta.addEventListener('input', () => {
            counter.textContent = `${ta.value.length} / ${max}`;
        });
    });

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
                success(html) {
                    horaSelect.innerHTML = html || '<option value="">Nenhum horário disponível</option>';
                },
                error() {
                    horaSelect.innerHTML = '<option value="">Erro ao carregar horários</option>';
                }
            });
        });
    }

    const senhaInput = loadModal.querySelector('#idSenha');
    const rptInput = loadModal.querySelector('#idRptSenha');
    const textoSenha = loadModal.querySelector('.texto-senha');
    const btnEnviar = loadModal.querySelector('#btnEnviar');

    if (senhaInput && rptInput) {
        window.verificarSenha = function () {
            const senha = senhaInput.value;
            const rpt = rptInput.value;
            const ok = senha.length >= 8 && senha === rpt;

            if (textoSenha) {
                textoSenha.textContent = senha.length < 8
                    ? 'Mínimo 8 caracteres'
                    : (senha !== rpt ? 'As senhas não coincidem' : '✓ Senhas iguais');
                textoSenha.style.color = ok ? 'var(--musgo-medio)' : 'var(--dourado)';
            }
            if (btnEnviar) btnEnviar.disabled = !ok;
        };
    }
}

function previewAvatar(input) {
    const preview = document.getElementById('avatarPreview');

    if (input.files && input.files[0]) {
        const arquivo = input.files[0];

        if (!arquivo.type.match(/^image\/(jpeg|jpg|png)$/)) {
            alert('Por favor, selecione um arquivo de imagem válido (JPG ou PNG).');
            input.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(arquivo);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}

function deletarEnviarCodigo(btn) {
    btn.disabled = true;
    btn.textContent = 'Enviando…';

    fetch('../controls/deletarEnviarCodigo.act.php', {
        method: 'POST'
    })
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

function delIniciarCodigo() {
    const boxes = document.querySelectorAll('.del-codigo-box');
    const hidden = document.getElementById('del-codigo-hidden');
    const btn = document.getElementById('btn-confirmar-delecao');

    function sync() {
        const val = [...boxes].map(b => b.value).join('');
        hidden.value = val;
        const completo = val.length === 6 && /^\d{6}$/.test(val);
        btn.disabled = !completo;
        btn.style.opacity = completo ? '1' : '0.4';
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
            txt.split('').forEach((c, j) => {
                if (boxes[j]) boxes[j].value = c;
            });
            boxes[Math.min(txt.length, 5)].focus();
            sync();
        });
    });

    boxes[0].focus();
}

function delIniciarTimer() {
    let s = 15 * 60;
    const txt = document.getElementById('del-timer-txt');
    const tick = setInterval(() => {
        s--;
        if (s <= 0) {
            clearInterval(tick);
            txt.textContent = '00:00';
            document.getElementById('btn-confirmar-delecao').disabled = true;
            document.getElementById('btn-confirmar-delecao').style.opacity = '0.4';
            return;
        }
        const m = String(Math.floor(s / 60)).padStart(2, '0');
        const sec = String(s % 60).padStart(2, '0');
        txt.textContent = `${m}:${sec}`;
    }, 1000);
}