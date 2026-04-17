<div id="modalAgendar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 id="modalNomeServico">Nome do Serviço</h2>
        <img id="modalImgServico" src="" style="width:100%; height:150px; object-fit:cover; border-radius:10px;">

        <form id="formAgendarRapido">
            <input type="hidden" id="modalIdServico" name="id_servico">

            <div class="input-group">
                <label>Data</label>
                <input type="date" name="data" required>
            </div>
            <div class="input-group">
                <label>Hora</label>
                <input type="time" name="hora" required>
            </div>
            <div class="input-group">
                <label>Observações</label>
                <textarea name="observacao" rows="3" style="resize:none;"></textarea>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn-confirmar">Confirmar</button>
                <button type="button" onclick="fecharModalAgendar()" class="btn-voltar">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalConfirmacao" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h3>Atenção</h3>
        <p>Deseja realmente cancelar?</p>
        <div class="modal-buttons">
            <button id="btnConfirmarSim" type="button" class="btn-confirmar">Sim</button>
            <button type="button" onclick="fecharModal()" class="btn-voltar">Não</button>
        </div>
    </div>
</div>