const loadModal = document.getElementById('body-load')
window.idParaCancelar = null;

// CANCELAR
window.cancelar = function (id) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'cancelar', id_registro: id },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    });
};

// --- MODAL AGENDAR ---
window.abrirModalAgendar = function (id, nome, imagem) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'agendar', id_registro: id, nome_servico: nome, img_servico: imagem },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
};

// --- MODAL DETALHES ---
window.abrirModalDetalhes = function (nome, descricao, imagem, data, hora, status) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { nome_servico: nome, desc: descricao, img_servico: imagem, data: data, hora_inicio: hora, status: status },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
};

// Modal avaliar
window.abrirAvaliar = function (id, nome, imagem, data, hora, status) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "avaliar", id_registro: id, nome_servico: nome, img_servico: imagem, data: data, hora: hora, status: status },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
}

function excluirOferecidos(id) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'excluir', id_registro: id },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
}

function pausarServico(id, ativo) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'pausar', id_registro: id, ativo: ativo },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
}

function abrirEdicao(id, nome, imagem, data, hora_inicio, hora_fim, status) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "editar", id_registro: id, nome_servico: nome, img_servico: imagem, data: data, hora_inicio: hora_inicio, hora_fim: hora_fim, status: status },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            modal.style.display = 'flex';
        }
    })
}

// Fechar
window.addEventListener('click', function (event) {
    const overlay = document.querySelector('.modal-overlay');

    if (event.target === overlay) fecharModais();
});

window.fecharModais = function () {
    const modal = document.querySelector('.modal-overlay');

    if (modal) modal.remove();
};