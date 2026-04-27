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
window.abrirModalAgendar = function (id, nome, imagem, hora_inicio, hora_fim, duracao) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'agendar', id_registro: id, nome_servico: nome, img_servico: imagem, hora_inicio: hora_inicio, hora_fim: hora_fim, duracao: duracao },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
};

// --- MODAL DETALHES ---
window.abrirModalDetalhes = function (nome, descricao, imagem, data, hora, status, obs) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { nome_servico: nome, desc: descricao, img_servico: imagem, data: data, hora_inicio: hora, status: status, observacao: obs },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
};

// Modal avaliar
window.abrirAvaliar = function (id, nome, imagem, data, hora, status, id_contrato) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "avaliar", id_registro: id, nome_servico: nome, img_servico: imagem, data: data, hora: hora, status: status, id_contrato: id_contrato },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function excluirOferecidos(id) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'excluir', id_registro: id },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function pausarServico(id, ativo) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: 'pausar', id_registro: id, ativo: ativo },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function abrirEdicao(id, nome, imagem, hora_inicio, hora_fim, duracao, status, descricao) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "editar", id_registro: id, nome_servico: nome, img_servico: imagem, hora_inicio: hora_inicio, hora_fim: hora_fim, duracao: duracao, status: status, desc: descricao },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function novoServico() {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "novo" },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function abrirModalAviso() {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: "aviso" },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

function editarPerfil(tipo) {
    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: { tipo: tipo },
        success: function (resp) {
            loadModal.innerHTML = resp

            const modal = document.querySelector('.modal-overlay')

            if (modal) modal.style.display = 'flex';
        }
    })
}

window.verAvaliacao = function (nome, imagem, comentario, nota) {
    if (!window.usuarioLogado) {
        window.location.href = "./util/setAviso.php";
        return;
    }

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: {
            tipo: "ver_avaliacao",
            nome_servico: nome,
            img_servico: imagem,
            comentario: comentario,
            nota: nota
        },
        success: function (resp) {
            loadModal.innerHTML = resp;

            const modal = document.querySelector('.modal-overlay');
            if (modal) modal.style.display = 'flex';
        }
    });
};

// Fechar
window.addEventListener('click', function (event) {
    const overlay = document.querySelector('.modal-overlay');

    if (event.target === overlay) fecharModais();
});

window.fecharModais = function () {
    const modal = document.querySelector('.modal-overlay');

    if (modal) modal.remove();
};

$(document).on('change', '[name="data"]', function () {
    const data = this.value;
    const idServico = document.querySelector('[name="id_servico"]').value;

    $.ajax({
        url: "./includes/modais.php",
        type: "GET",
        data: {
            tipo: 'horarios',
            data: data,
            id_registro: idServico
        },
        success: function (resp) {
            document.getElementById('horarios').innerHTML = resp;
        }
    });
});