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