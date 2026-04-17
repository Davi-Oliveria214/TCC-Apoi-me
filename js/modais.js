window.idParaCancelar = null;

// CANCELAR
window.cancelar = function (id) {
    window.idParaCancelar = id;

    const modal = document.getElementById('modalConfirmacao');
    if (modal) modal.style.display = 'flex';
};

// FECHAR MODAL CANCELAR
window.fecharModal = function () {
    const modal = document.getElementById('modalConfirmacao');
    if (modal) modal.style.display = 'none';

    window.idParaCancelar = null;
};

// MODAL AGENDAR
window.abrirModalAgendar = function (id, nome, imagem) {
    const modal = document.getElementById('modalAgendar');

    if (modal) {
        document.getElementById('modalIdServico').value = id;
        document.getElementById('modalNomeServico').innerText = nome;
        document.getElementById('modalImgServico').src = imagem;

        modal.style.display = 'flex';
    }
};

window.fecharModalAgendar = function () {
    const modal = document.getElementById('modalAgendar');
    if (modal) modal.style.display = 'none';
};

document.addEventListener('DOMContentLoaded', function () {
    const btnSim = document.getElementById('btnConfirmarSim');

    if (btnSim) {
        btnSim.addEventListener('click', function () {
            if (!idParaCancelar) return;

            $.ajax({
                type: "POST",
                url: "./controls/cancelar.php",
                data: { resp: idParaCancelar },
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert("Erro ao cancelar o serviço.");
                }
            });

            window.fecharModal();
        });
    }

    const formAgendar = $('#formAgendarRapido');

    if (formAgendar.length) {
        formAgendar.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "./controls/agendar.act.php",
                data: $(this).serialize(),
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert("Erro ao processar agendamento.");
                }
            });
        });
    }
});

window.addEventListener('click', function (event) {
    const modalAgendar = document.getElementById('modalAgendar');
    const modalConfirmar = document.getElementById('modalConfirmacao');

    if (event.target && event.target.id === 'modalAgendar') {
        fecharModalAgendar();
    }

    if (event.target && event.target.id === 'modalConfirmacao') {
        fecharModal();
    }
});