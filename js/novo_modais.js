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