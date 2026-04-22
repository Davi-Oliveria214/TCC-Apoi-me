<?php
require_once(__DIR__ . '/../conexao.php');
@session_start();

$tipo = $_GET['tipo'] ?? '';
$id_registro = $_GET['id_registro'] ?? '';
$nome_servico = $_GET['nome_servico'] ?? '';
$desc = $_GET['desc'] ?? ''; // Ajustado para bater com o JS
$img_servico = $_GET['img_servico'] ?? '';
$data = $_GET['data'] ?? '';
$hora_inicio = $_GET['hora_inicio'] ?? '';
$hora_fim = $_GET['hora_fim'] ?? '';
$status = $_GET['status'] ?? '';
$ativo = $_GET['ativo'] ?? '';
?>

<div class="modal-overlay" style="display: flex;">

    <?php if ($tipo == 'agendar'): ?>
        <form action="../controls/agendar.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id_registro ?>">

            <div class="modal-header">
                <div class="modal-icon">📅</div>
                <h3>Agendar: <?php echo $nome_servico ?></h3>
            </div>

            <div class="modal-body">
                <img src="<?php echo $img_servico ?>" class="modal-img-destaque">
                <div class="input-row">
                    <div class="input-group">
                        <label>Data</label>
                        <input type="date" name="data" required>
                    </div>
                    <div class="input-group">
                        <label>Hora</label>
                        <input type="time" name="hora" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Observações</label>
                    <textarea name="observacao" rows="3" placeholder="Algo que o prestador precise saber?"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-confirmar">Confirmar Agendamento</button>
                <button type="button" onclick="fecharModais()" class="btn-voltar">Voltar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'cancelar' || $tipo == 'excluir') :
        $isExcluir = ($tipo == 'excluir');
        $action = $isExcluir ? "./controls/excluir.php" : "./controls/cancelar.php";
    ?>
        <form action="<?php echo $action ?>" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="<?php echo $isExcluir ? 'id_servico' : 'resp' ?>" value="<?php echo $id_registro ?>">

            <div class="modal-header">
                <div class="modal-icon icon-danger"><?php echo $isExcluir ? '🗑️' : '⚠️' ?></div>
                <h3><?php echo $isExcluir ? 'Excluir Serviço?' : 'Cancelar?' ?></h3>
            </div>

            <div class="modal-body">
                <p><?php echo $isExcluir
                        ? 'Esta ação é permanente. Todas as reservas futuras vinculadas serão removidas.'
                        : 'Tem certeza que deseja cancelar esta solicitação?' ?></p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-perigo">Confirmar</button>
                <button type="button" onclick="fecharModais()" class="btn-voltar">Voltar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'avaliar'): ?>

        <form action="./controls/avaliarServico.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">

            <div class="modal-header">
                <div class="modal-icon">⭐</div>
                <h3>Avaliar Serviço</h3>
            </div>

            <div class="modal-body">
                <div class="mini-card-servico">
                    <img src="<?php echo $img_servico ?>">
                    <div>
                        <strong><?php echo $nome_servico ?></strong>
                        <small><?php echo $status ?> — <?php echo $data ?></small>
                    </div>
                </div>

                <div class="star-rating">
                    <input type="hidden" name="nota" class="nota-input" value="0" required>
                    <div class="stars">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="star-label">Clique nas estrelas</span>
                </div>

                <div class="input-group">
                    <textarea class="comment-area" name="comentario" maxlength="500" placeholder="Como foi sua experiência?"></textarea>
                    <div class="char-count">0 / 500</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-confirmar">Enviar Avaliação</button>
                <button type="button" onclick="fecharModais()" class="btn-voltar">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar' || $tipo == 'novo') :
        $isEdit = ($tipo == 'editar');
        $action = $isEdit ? "./controls/editar_servico.act.php" : "./controls/addServico.php";
    ?>
        <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" class="modal-content modal-padrao modal-large ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id_registro ?>">

            <div class="modal-header">
                <div class="modal-icon"><?php echo $isEdit ? '📝' : '➕' ?></div>
                <h3><?php echo $isEdit ? 'Editar Anúncio' : 'Novo Anúncio' ?></h3>
            </div>

            <div class="modal-body corpo-scroll">
                <div class="input-group">
                    <label>Nome do Anúncio</label>
                    <input type="text" name="nome" value="<?php echo $nome_servico ?>" required>
                </div>

                <?php if (!$isEdit): ?>
                    <div class="input-group">
                        <label>Categoria</label>
                        <select name="categoria" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="input-row">
                    <div class="input-group">
                        <label>Início</label>
                        <input type="time" name="hora_inicio" value="<?php echo $hora_inicio ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Término</label>
                        <input type="time" name="hora_fim" value="<?php echo $hora_fim ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Descrição</label>
                    <textarea name="descricao" rows="4" required><?php echo $desc ?></textarea>
                </div>

                <div class="input-group">
                    <label>Imagem do Serviço</label>
                    <label for="idImagem" class="upload-area">
                        <img id="preview" class="preview-imagem" src="<?php echo $img_servico ?: './img/placeholder.png' ?>">
                        <div class="upload-overlay"><span>📷 Alterar Foto</span></div>
                    </label>
                    <input type="file" name="imagem" id="idImagem" class="input-imagem" accept="image/*" style="display: none;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-confirmar"><?php echo $isEdit ? 'Salvar Alterações' : 'Publicar Anúncio' ?></button>
                <button type="button" onclick="fecharModais()" class="btn-voltar">Cancelar</button>
            </div>
        </form>

    <?php else : ?>
        <div class="modal-content modal-padrao">
            <div class="modal-header">
                <div class="modal-icon">🔍</div>
                <h3>Detalhes do Agendamento</h3>
            </div>
            <div class="modal-body">
                <img src="<?php echo $img_servico ?>" class="modal-img-destaque">
                <div class="detalhes-lista">
                    <div class="detalhe-item">
                        <label>Serviço</label>
                        <p><?php echo $nome_servico ?></p>
                    </div>
                    <div class="detalhe-item">
                        <label>Descrição</label>
                        <p><?php echo $desc ?></p>
                    </div>
                    <div class="input-row">
                        <div class="detalhe-item">
                            <label>Data</label>
                            <p><?php echo date('d/m/Y', strtotime($data)) ?></p>
                        </div>
                        <div class="detalhe-item">
                            <label>Hora</label>
                            <p><?php echo $hora_inicio ?></p>
                        </div>
                    </div>
                    <div class="detalhe-item">
                        <label>Status</label>
                        <span class="status-badge"><?php echo $status ?></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="fecharModais()" class="btn-confirmar">Entendi</button>
            </div>
        </div>
    <?php endif ?>

</div>