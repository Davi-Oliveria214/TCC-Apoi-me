<?php
require_once(__DIR__ . '/../conexao.php');
@session_start();
$tipo = $_GET['tipo'];
$id_registro = $_GET['id_registro'] ?? '';
$nome_servico = $_GET['nome_servico'] ?? '';
$desc = $_GET['descricao'] ?? '';
$img_servico = $_GET['img_servico'] ?? '';
$data = $_GET['data'] ?? '';
$hora_inicio = $_GET['hora_inicio'] ?? '';
$hora_inicio = $_GET['hora_fim'] ?? '';
$status = $_GET['status'] ?? '';
$ativo = $_GET['ativo'] ?? '';

if ($tipo == 'agendar'):
?>
    <div class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h2 id="modalNomeServico"><?php echo $nome_servico ?></h2>
            <img id="modalImgServico" src="<?php echo $img_servico ?>" style="width:100%; height:150px; object-fit:cover; border-radius:10px;">

            <form action="../controls/agendar.act.php" method="post" class="ativar-load">
                <input type="hidden" id="modalIdServico" name="id_servico" value="<?php echo $id_registro ?>">

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
                    <button type="button" onclick="fecharModais()" class="btn-voltar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

<?php elseif ($tipo == 'cancelar') : ?>

    <div class="modal-overlay" style="display:none;">
        <form action="./controls/cancelar.php" method="post" class="modal-content ativar-load">
            <h3>Atenção</h3>
            <p>Deseja realmente cancelar?</p>
            <input type="hidden" style="display: none;" name="resp" id="idCancelar" value="<?php echo $id_registro ?>">
            <div class="modal-buttons">
                <button type="submit" class="btn-confirmar">Sim</button>
                <button type="button" onclick="fecharModais()" class="btn-voltar">Não</button>
            </div>
        </form>
    </div>

<?php elseif ($tipo == 'avaliar'): ?>

    <div class="modal-overlay" style="display:none;">
        <form action="./controls/avaliarServico.act.php" method="post" class="cardAvaliar ativar-load">
            <div class="cardAvaliar-top">
                <div class="service-icon"><img src="<?php echo $img_servico ?>" alt=""></div>
                <div class="cardAvaliar-info">
                    <div class="service-name"><?php echo $nome_servico ?></div>
                    <div class="service-meta">Troca de disjuntor — <?php echo $data ?></div>
                </div>
                <span class="badge done"><?php echo $status ?></span>
            </div>
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">

            <div class="star-row">
                <input type="hidden" name="nota" class="nota-input" value="0" required>

                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
                <span class="star-label">Toque para avaliar</span>
            </div>

            <textarea class="comment-areaAvaliacao" maxlength="500" placeholder="Comentário opcional" name="comentario" id="area-comentario"></textarea>
            <div class="NumCaracteres" id="char-count">0 / 500</div>
            <button type="submit" class="submit-btnAvaliacaoSER">Enviar avaliação</button>
        </form>
    </div>

<?php elseif ($tipo == 'excluir') : ?>

    <div class="modal-overlay" style="display: none;">
        <form action="./controls/excluir.php" method="post" class="modal-content ativar-load">
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">
            <div class="modal-icone">🗑️</div>
            <h3>Excluir Serviço?</h3>
            <p>Essa ação não pode ser desfeita. Todas as reservas futuras vinculadas a este serviço serão canceladas.</p>
            <div class="modal-buttons">
                <button type="submit" class="btn-confirmar">Excluir</button>
                <button type="button" class="btn-voltar" onclick="fecharModais()">Cancelar</button>
            </div>
        </form>
    </div>

<?php elseif ($tipo == 'pausar') : ?>

    <div class="modal-overlay" style="display: none;">
        <form action="./controls/pausar.php" method="post" class="modal-content ativar-load">
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">
            <input type="hidden" value="<?php echo $ativo ? 'false' : 'true' ?>" name="novo_status">

            <div class="modal-icone">⏸️</div>

            <h3><?php echo $ativo ? 'Pausar Serviço?' : 'Voltar Serviço?' ?></h3>

            <p>
                <?php echo $ativo
                    ? 'Ao pausar, seu serviço ficará oculto para novos agendamentos, mas as reservas atuais seguem mantidas.'
                    : 'Ao reativar, seu serviço voltará a ficar visível para todos os moradores do condomínio.' ?>
            </p>

            <div class="modal-buttons">
                <button type="submit" class="btn-confirmar">Confirmar</button>
                <button type="button" class="btn-voltar" onclick="fecharModais()">Voltar</button>
            </div>
        </form>
    </div>
<?php elseif ($tipo == 'editar') : ?>

    <div class="modal-overlay" style="display: flex;">
        <form action="./controls/editar_servico.act.php" method="post" enctype="multipart/form-data" class="modal-content modal-editar ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id_registro ?>">

            <div class="modal-header-editar">
                <div class="modal-icone">📝</div>
                <h3>Editar Serviço</h3>
            </div>

            <div class="modal-corpo-scroll">
                <div class="input-group">
                    <label>Nome do Serviço</label>
                    <input type="text" name="nome" value="<?php echo $nome_servico ?>" required>
                </div>

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
                    <label>Alterar Imagem (Opcional)</label>
                    <div class="upload-wrapper">
                        <label for="edit-img" class="label-preview">
                            <img id="preview" src="<?php echo $img_servico ?>" alt="Preview">
                            <div class="overlay-upload">
                                <span>📷 Alterar Foto</span>
                            </div>
                        </label>

                        <input type="file" name="imagem" id="edit-img" accept="image/*" style="display: none;">
                        <p class="file-hint">Clique na imagem para alterar. Mantenha vazio para não mudar.</p>
                    </div>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn-confirmar">Salvar Alterações</button>
                <button type="button" class="btn-voltar" onclick="fecharModais()">Cancelar</button>
            </div>
        </form>
        <script>
            const imageInput = document.getElementById('edit-img');
            const preview = document.getElementById('preview');

            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        }

                        reader.readAsDataURL(file);
                    }
                });
            }
        </script>
    </div>

<?php elseif ($tipo == 'novo') : ?>
    
    <form action="./controls/addServico.php" method="post" class="form-add ativar-load" enctype="multipart/form-data">
        <h1>Criar Anúncio</h1>
        <section class="box-add">
            <div class="box-addServicos">
                <input type="text" id="idNome" name="nome" placeholder="Nome do anúncio" required>
            </div>

            <div class="box-addServicos">
                <select name="categoria" id="idCategorias" required>
                    <option value="" disabled selected hidden>Tipo do serviço</option>
                    <?php
                    $sql = request("categorias?select=*", "GET");

                    if (!empty($sql) && !isset($sql['error'])):
                        foreach ($sql as $categoria):
                    ?>
                            <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['nome'] ?></option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="box-addServicos">
                <input type="date" id="idData" name="data" placeholder="Data (Opicional)">
            </div>

            <div class="box-addServicos">
                <input type="time" id="idHorario" name="horario" placeholder="Horario (Opicional)">
            </div>

            <div class="box-addServicos">
                <textarea name="descricao" id="idDescricao" style="resize: none;" placeholder="Descricao" required></textarea>
            </div>

            <div class="box-addServicos">
                <label for="idImagem">Clique para selecionar uma imagem</label>
                <input type="file" id="idImagem" name="imagem" style="display: none;">
                <img src="" alt="Prévia da imagem" id="preview">
            </div>
        </section>

        <div><button type="submit">Adicionar</button></div>
    </form>

<?php else : ?>

    <div id="modalDetalhes" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h2 id="detalheNome"><?php echo $nome_servico ?></h2>
            <img id="detalheImg" src="<?php echo $img_servico ?>" alt="Serviço">

            <div class="detalhe-corpo">
                <div class="detalhe-item">
                    <label>Descrição</label>
                    <p id="detalheDesc"><?php echo $desc ?></p>
                </div>

                <div class="detalhe-row">
                    <div class="detalhe-item">
                        <label>Data</label>
                        <p id="detalheData"><?php echo date('d/m/Y', strtotime($data)); ?></p>
                    </div>
                    <div class="detalhe-item">
                        <label>Hora</label>
                        <p id="detalheHora"><?php echo date('H:i', strtotime($hora_inicio)) ?></p>
                    </div>
                </div>

                <div class="detalhe-item">
                    <label>Status do Agendamento</label>
                    <p id="detalheStatus" class="status-badge"><?php echo $status ?></p>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" onclick="fecharModais()" class="btn-confirmar">Fechar</button>
            </div>
        </div>
    </div>

<?php endif ?>