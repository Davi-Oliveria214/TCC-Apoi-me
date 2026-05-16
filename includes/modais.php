<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo('GET');
require_once(__DIR__ . '/../conexao.php');

$tipo = $_GET['tipo'] ?? '';
$id   = $_GET['id']   ?? '';

if ($tipo === 'horarios') {
    $data       = $_GET['data']       ?? '';
    $id_servico = $_GET['id_servico'] ?? '';

    $servico = request("servicos?id=eq.$id_servico");

    if (empty($servico) || isset($servico['error'])) {
        echo "<option value=''>Serviço não encontrado</option>";
        exit;
    }

    $s          = $servico[0];
    $duracaoMin = (int) date('i', strtotime($s['duracao'])) + (int) date('H', strtotime($s['duracao'])) * 60;
    $reservas   = request("contratados?dia=eq.$data&id_servico=eq.$id_servico");
    $ocupados   = [];

    if (!empty($reservas) && !isset($reservas['error'])) {
        foreach ($reservas as $r) {
            $ocupados[] = date('H:i', strtotime($r['hora']));
        }
    }

    $inicio    = strtotime($s['hora_inicio']);
    $fim       = strtotime($s['hora_fim']);
    $intervalo = max(1, $duracaoMin) * 60;

    for ($t = $inicio; $t <= $fim; $t += $intervalo) {
        $hora      = date('H:i', $t);
        $bloqueado = in_array($hora, $ocupados);
        $disabled  = $bloqueado ? 'disabled' : '';
        $sufixo    = $bloqueado ? ' (Indisponível)' : '';
        echo "<option value='$hora' $disabled>$hora$sufixo</option>";
    }
    exit;
}

function esc($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES);
}

function notaStars(int $nota, bool $inline = false): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $cor   = $i <= $nota ? 'var(--dourado)' : 'var(--cinza)';
        $html .= "<span style='color:$cor; font-size:" . ($inline ? '18px' : '22px') . ";'>★</span>";
    }
    return $html;
}

$usuario = request("usuarios?id=eq.{$_SESSION['id']}&select=nome,email,codigo");
$usuario = (!empty($usuario) && !isset($usuario['error'])) ? $usuario[0] : [];
?>

<div class="modal-overlay" style="display:flex;">

    <?php
    /* =====================================================================
   AGENDAR
   id = id do serviço
   ===================================================================== */
    if ($tipo === 'agendar'):
        $servico = request("servicos?id=eq.$id");
        if (empty($servico) || isset($servico['error'])): ?>
            <div class="modal-content modal-alerta">
                <div class="modal-header">
                    <h3>Erro</h3>
                </div>
                <div class="modal-body">
                    <p>Serviço não encontrado.</p>
                </div>
                <div class="modal-footer"><button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button></div>
            </div>
        <?php else:
            $s          = $servico[0];
            $horaInicio = date('H:i', strtotime($s['hora_inicio']));
            $horaFim    = date('H:i', strtotime($s['hora_fim']));
        ?>
            <form action="../controls/agendar.act.php" method="post" class="modal-content modal-padrao ativar-load">
                <input type="hidden" name="id_servico" value="<?= esc($id) ?>">

                <div class="modal-header">
                    <h3>Agendar: <?= esc($s['nome']) ?></h3>
                </div>

                <div class="modal-body">
                    <img src="<?= esc($s['imagem']) ?>" class="modal-img-destaque" alt="<?= esc($s['nome']) ?>">

                    <div class="input-row">
                        <div class="input-group">
                            <label>Data</label>
                            <input type="date" name="data" id="modal-data-input"
                                min="<?= date('Y-m-d') ?>" required
                                data-servico="<?= esc($id) ?>">
                        </div>
                        <div class="input-group">
                            <label>Hora</label>
                            <select name="hora" id="modal-hora-select" required>
                                <option value="">Selecione uma data</option>
                            </select>
                            <small class="helper-text">Disponível entre <?= $horaInicio ?> e <?= $horaFim ?></small>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Observações</label>
                        <textarea name="observacao" rows="3"
                            placeholder="Algo que o prestador precise saber?"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-modais">Confirmar Agendamento</button>
                    <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Voltar</button>
                </div>
            </form>
        <?php endif; ?>

    <?php

    /* =====================================================================
   CANCELAR AGENDAMENTO
   id = id do contrato (resp)
   ===================================================================== */
    elseif ($tipo === 'cancelar'): ?>
        <form action="../controls/cancelar.php" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="resp" value="<?= esc($id) ?>">

            <div class="modal-header">
                <h3>Cancelar agendamento?</h3>
            </div>

            <div class="modal-body">
                <p>Tem certeza que deseja cancelar esta solicitação? Esta ação não pode ser desfeita.</p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais btn-modais--danger">Sim, cancelar</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Voltar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EXCLUIR SERVIÇO
   id = id do serviço
   ===================================================================== */
    elseif ($tipo === 'excluir'): ?>
        <form action="../controls/excluir.php" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id ?>">

            <div class="modal-header">
                <h3>Excluir serviço?</h3>
            </div>

            <div class="modal-body">
                <p>Esta ação é <strong>permanente</strong>. Todas as reservas futuras vinculadas a este serviço serão removidas.</p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais btn-modais--danger">Sim, excluir</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Voltar</button>
            </div>
        </form>

        <?php

    /* =====================================================================
   AVALIAR SERVIÇO
   id = id do contrato
   ===================================================================== */
    elseif ($tipo === 'avaliar'):
        $contrato = request("contratados?id=eq.$id&select=id,dia,hora,confirmado,servicos(id,nome,imagem)");
        if (empty($contrato) || isset($contrato['error'])): ?>
            <div class="modal-content modal-alerta">
                <div class="modal-header">
                    <h3>Erro</h3>
                </div>
                <div class="modal-body">
                    <p>Contrato não encontrado.</p>
                </div>
                <div class="modal-footer"><button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button></div>
            </div>
        <?php else:
            $c       = $contrato[0];
            $s       = $c['servicos'];
            $dataFmt = date('d/m/Y', strtotime($c['dia']));
            $horaFmt = date('H:i',   strtotime($c['hora']));
            $status  = $c['confirmado'] ? 'Confirmado' : 'Pendente';
        ?>
            <form action="../controls/avaliarServico.act.php" method="post" class="modal-content modal-padrao ativar-load">
                <input type="hidden" name="id_servico" value="<?= esc($s['id']) ?>">
                <input type="hidden" name="id_contrato" value="<?= esc($c['id']) ?>">

                <div class="modal-header">
                    <h3>Avaliar Serviço</h3>
                </div>

                <div class="modal-body">
                    <div class="mini-card-servico">
                        <img src="<?= esc($s['imagem']) ?>" alt="<?= esc($s['nome']) ?>">
                        <div>
                            <strong><?= esc($s['nome']) ?></strong>
                            <small><?= esc($status) ?> — <?= $dataFmt ?> às <?= $horaFmt ?></small>
                        </div>
                    </div>

                    <div class="star-rating">
                        <input type="hidden" name="nota" class="nota-input" value="0" required>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-value="<?= $i ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="star-label">Clique nas estrelas para avaliar</span>
                    </div>

                    <div class="input-group">
                        <textarea class="comment-area" name="comentario" maxlength="500"
                            placeholder="Como foi sua experiência?"></textarea>
                        <div class="char-count">0 / 500</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-modais">Enviar Avaliação</button>
                    <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
                </div>
            </form>
        <?php endif; ?>

        <?php

    /* =====================================================================
   VER AVALIAÇÃO
   id = id da avaliação
   ===================================================================== */
    elseif ($tipo === 'ver_avaliacao'):
        $avaliacao = request("avaliacoes?id=eq.$id");

        if (empty($avaliacao) || isset($avaliacao['error'])): ?>
            <div class="modal-content modal-alerta">
                <div class="modal-header">
                    <h3>Erro</h3>
                </div>
                <div class="modal-body">
                    <p>Avaliação não encontrada para este serviço.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button>
                </div>
            </div>
        <?php else:
            $av = $avaliacao[0];
            $dataFmt = !empty($av['data']) ? date('d/m/Y', strtotime($av['data'])) : '—';
            $horaFmt = !empty($av['horario']) ? date('H:i', strtotime($av['horario'])) : '—';
        ?>
            <div class="modal-content modal-padrao">
                <div class="modal-header">
                    <h3>Avaliação Realizada</h3>
                </div>

                <div class="modal-body">
                    <div class="mini-card-servico" style="margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <small style="text-transform: uppercase; letter-spacing: 1px; color: var(--dourado-palido); font-size: 10px; font-weight: 700; display: block; margin-bottom: 2px;">Serviço Contratado</small>
                            <strong style="font-size: 16px;"><?= esc($av['nome_servico'] ?? 'Serviço') ?></strong>
                        </div>
                    </div>

                    <div class="star-rating" style="padding: 10px 0 16px;">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?= $i <= $av['nota'] ? 'active' : '' ?>" style="cursor: default; pointer-events: none;">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="star-label">Nota dada: <?= $av['nota'] ?> de 5</span>
                    </div>

                    <div class="detalhes-lista">
                        <div class="input-row" style="margin-bottom: 0;">
                            <div class="detalhe-item">
                                <label>Cliente</label>
                                <p><?= esc($av['nome_cliente'] ?? 'Não informado') ?></p>
                            </div>
                            <div class="detalhe-item">
                                <label>Prestador</label>
                                <p><?= esc($av['nome_prestador'] ?? 'Não informado') ?></p>
                            </div>
                        </div>

                        <div class="input-row" style="margin-bottom: 0; margin-top: 4px;">
                            <div class="detalhe-item">
                                <label>Data da Realização</label>
                                <p><?= $dataFmt ?></p>
                            </div>
                            <div class="detalhe-item">
                                <label>Horário</label>
                                <p><?php echo $horaFmt ?></p>
                            </div>
                        </div>

                        <div class="detalhe-item" style="margin-top: 8px;">
                            <label>Comentário do Morador</label>
                            <p style="background: rgba(255, 255, 255, 0.03); padding: 14px; border-radius: 12px; border: 1px solid rgba(176, 130, 43, 0.15); font-style: italic; color: rgba(245, 230, 192, 0.85); line-height: 1.6;">
                                "<?= esc($av['comentario'] ?? 'Nenhum comentário preenchido.') ?>"
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="fecharModais()" class="btn-modais">Voltar</button>
                </div>
            </div>
        <?php endif; ?>
        <?php

    /* =====================================================================
   DETALHES DO AGENDAMENTO
   id = id do contrato
   ===================================================================== */
    elseif ($tipo === 'detalhes'):
        $contrato = request("contratados?id=eq.$id&select=dia,hora,confirmado,observacao,servicos(nome,descricao,imagem)");
        if (empty($contrato) || isset($contrato['error'])): ?>
            <div class="modal-content modal-alerta">
                <div class="modal-header">
                    <h3>Erro</h3>
                </div>
                <div class="modal-body">
                    <p>Agendamento não encontrado.</p>
                </div>
                <div class="modal-footer"><button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button></div>
            </div>
        <?php else:
            $c       = $contrato[0];
            $s       = $c['servicos'];
            $dataFmt = date('d/m/Y', strtotime($c['dia']));
            $horaFmt = date('H:i',   strtotime($c['hora']));
            $status  = $c['confirmado'] ? 'Confirmado' : 'Pendente';
        ?>
            <div class="modal-content modal-padrao">
                <div class="modal-header">
                    <h3>Detalhes do Agendamento</h3>
                </div>

                <div class="modal-body">
                    <img src="<?= esc($s['imagem']) ?>" class="modal-img-destaque" alt="<?= esc($s['nome']) ?>">

                    <div class="detalhes-lista">
                        <div class="detalhe-item">
                            <label>Serviço</label>
                            <p><?= esc($s['nome']) ?></p>
                        </div>
                        <div class="detalhe-item">
                            <label>Descrição</label>
                            <p><?= esc($s['descricao']) ?></p>
                        </div>
                        <div class="input-row">
                            <div class="detalhe-item">
                                <label>Data</label>
                                <p><?= $dataFmt ?></p>
                            </div>
                            <div class="detalhe-item">
                                <label>Hora</label>
                                <p><?= $horaFmt ?></p>
                            </div>
                        </div>
                        <div class="input-row">
                            <div class="detalhe-item">
                                <label>Status</label>
                                <p class="status-badge"><?= esc($status) ?></p>
                            </div>
                            <div class="detalhe-item">
                                <label>Observação</label>
                                <p><?= !empty($c['observacao']) ? esc($c['observacao']) : '—' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="fecharModais()" class="btn-modais">Entendi</button>
                </div>
            </div>
        <?php endif; ?>

        <?php

    /* =====================================================================
   PAUSAR / ATIVAR SERVIÇO
   id = id do serviço
   ===================================================================== */
    elseif ($tipo === 'pausar'):
        $servico = request("servicos?id=eq.$id&select=id,status,nome");
        if (empty($servico) || isset($servico['error'])): ?>
            <div class="modal-content modal-alerta">
                <div class="modal-header">
                    <h3>Erro</h3>
                </div>
                <div class="modal-body">
                    <p>Serviço não encontrado.</p>
                </div>
                <div class="modal-footer"><button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button></div>
            </div>
        <?php else:
            $ativo   = $servico[0]['status'];
            $titulo  = $ativo ? 'Pausar serviço?' : 'Ativar serviço?';
            $msg     = $ativo
                ? 'O serviço ficará <strong>indisponível</strong> para novos agendamentos.'
                : 'O serviço voltará a ficar <strong>disponível</strong> para agendamentos.';
            $btnTxt  = $ativo ? 'Pausar' : 'Ativar';
            $novoStatus = $ativo ? 'false' : 'true';
        ?>
            <form action="../controls/pausar.act.php" method="post" class="modal-content modal-alerta ativar-load">
                <input type="hidden" name="id_servico" value="<?= esc($id) ?>">
                <input type="hidden" name="status" value="<?= $novoStatus ?>">

                <div class="modal-header">
                    <h3><?= $titulo ?></h3>
                </div>

                <div class="modal-body">
                    <p><?= $msg ?></p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-modais"><?= $btnTxt ?></button>
                    <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
                </div>
            </form>
        <?php endif; ?>

    <?php

    /* =====================================================================
   NOVO / EDITAR ANÚNCIO
   id = id do serviço (vazio para novo)
   ===================================================================== */
    elseif ($tipo === 'novo' || $tipo === 'editar'):
        $isEdit  = ($tipo === 'editar');
        $action  = $isEdit ? '../controls/editar_servico.act.php' : '../controls/addServico.php';

        $s = [];
        if ($isEdit && $id) {
            $res = request("servicos?id=eq.$id");
            $s   = (!empty($res) && !isset($res['error'])) ? $res[0] : [];
        }

        $nomeServico = $s['nome']        ?? '';
        $imgServico  = $s['imagem']      ?? '';
        $horaIni     = isset($s['hora_inicio']) ? date('H:i', strtotime($s['hora_inicio'])) : '';
        $horaFim     = isset($s['hora_fim'])    ? date('H:i', strtotime($s['hora_fim']))    : '';
        $duracao     = isset($s['duracao'])     ? date('H:i', strtotime($s['duracao']))     : '';
        $descricao   = $s['descricao']   ?? '';
    ?>
        <form action="<?= $action ?>" method="post" enctype="multipart/form-data"
            class="modal-content modal-padrao modal-large ativar-load">
            <input type="hidden" name="id_servico" value="<?= esc($id) ?>">

            <div class="modal-header">
                <h3><?= $isEdit ? 'Editar Anúncio' : 'Novo Anúncio' ?></h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Nome do Anúncio</label>
                    <input type="text" name="nome" value="<?= esc($nomeServico) ?>" required>
                </div>

                <?php if (!$isEdit): ?>
                    <div class="input-group">
                        <label>Categoria</label>
                        <select name="categoria" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                            <?php
                            $categorias = request("categorias?select=id,nome&order=nome.asc");
                            if (!empty($categorias) && !isset($categorias['error'])):
                                foreach ($categorias as $cat):
                            ?>
                                    <option value="<?= esc($cat['id']) ?>"><?= esc($cat['nome']) ?></option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="input-row">
                    <div class="input-group">
                        <label>Início</label>
                        <input type="time" name="hora_inicio" value="<?= esc($horaIni) ?>"
                            list="horarios-comuns" required>
                    </div>
                    <div class="input-group">
                        <label>Término</label>
                        <input type="time" name="hora_fim" value="<?= esc($horaFim) ?>"
                            list="horarios-comuns" required>
                    </div>
                    <datalist id="horarios-comuns">
                        <?php foreach (['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'] as $h): ?>
                            <option value="<?= $h ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="input-group">
                    <label>Duração por atendimento</label>
                    <input type="time" name="duracao" value="<?= esc($duracao) ?>"
                        list="tempos-comuns" required>
                    <datalist id="tempos-comuns">
                        <?php foreach (['00:30', '01:00', '01:30', '02:00', '02:30', '03:00'] as $t): ?>
                            <option value="<?= $t ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="input-group">
                    <label>Descrição</label>
                    <textarea name="descricao" rows="4" required><?= esc($descricao) ?></textarea>
                </div>

                <div class="input-group">
                    <label>Imagem do Serviço</label>
                    <label for="idImagem" class="upload-area">
                        <img id="preview" class="preview-imagem"
                            src="<?= esc($imgServico) ?>"
                            style="<?= empty($imgServico) ? 'display:none;' : '' ?>">
                        <div class="upload-overlay"><span>Alterar Foto</span></div>
                    </label>
                    <input type="file" name="imagem" id="idImagem" class="input-imagem"
                        accept="image/*" style="display:none;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">
                    <?= $isEdit ? 'Salvar Alterações' : 'Publicar Anúncio' ?>
                </button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR NOME
   (sem id — usa sessão)
   ===================================================================== */
    elseif ($tipo === 'editar_nome'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="nome">

            <div class="modal-header">
                <h3>Alterar Nome</h3>
            </div>

            <div class="modal-body">
                <p>Como você gostaria de ser chamado na plataforma?</p>
                <div class="input-group">
                    <label>Nome atual</label>
                    <input type="text" value="<?= esc($usuario['nome'] ?? '') ?>" disabled>
                </div>
                <div class="input-group">
                    <label>Novo nome</label>
                    <input type="text" name="valor" placeholder="Digite o novo nome" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Nome</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR E-MAIL
   ===================================================================== */
    elseif ($tipo === 'editar_email'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="email">

            <div class="modal-header">
                <h3>Alterar E-mail</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>E-mail atual</label>
                    <input type="email" value="<?= esc($usuario['email'] ?? '') ?>" disabled>
                </div>
                <div class="input-group">
                    <label>Novo e-mail</label>
                    <input type="email" name="valor" placeholder="exemplo@email.com" required>
                </div>
                <p style="font-size:13px; color:var(--cinza); margin-top:6px;">
                    Você precisará usar este novo e-mail no próximo login.
                </p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Atualizar E-mail</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR SENHA
   ===================================================================== */
    elseif ($tipo === 'editar_senha'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="senha">

            <div class="modal-header">
                <h3>Alterar Senha</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Senha Atual</label>
                    <div class="input-container">
                        <input type="password" name="senha_atual"
                            placeholder="Digite sua senha atual" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Nova Senha</label>
                    <div class="input-container">
                        <input type="password" name="nova_senha" id="idSenha"
                            minlength="8"
                            onkeydown="if(event.key===' ')event.preventDefault()"
                            oninput="verificarSenha()"
                            placeholder="Mínimo 8 caracteres" required>
                        <button type="button" class="olho-btn" onclick="toggleSenha('idSenha', this)" aria-label="Mostrar senha">
                            <img id="olho-idSenha" src="./icon/visibility.png" class="olho-icon" alt="Mostrar">
                        </button>
                    </div>
                    <p class="texto-senha" style="color:var(--musgo-medio);"></p>
                </div>

                <div class="input-group">
                    <label>Confirmar Nova Senha</label>
                    <div class="input-container">
                        <input type="password" name="confirmar_senha" id="idRptSenha"
                            minlength="8"
                            onkeydown="if(event.key===' ')event.preventDefault()"
                            oninput="verificarSenha()"
                            placeholder="Repita a nova senha" required>
                        <button type="button" class="olho-btn" onclick="toggleSenha('idRptSenha', this)" aria-label="Mostrar senha">
                            <img id="olho-idRptSenha" src="./icon/visibility.png" class="olho-icon" alt="Mostrar">
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnEnviar" class="btn-modais">Redefinir Senha</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR CÓDIGO DO CONDOMÍNIO
   ===================================================================== */
    elseif ($tipo === 'editar_codigo'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="codigo">

            <div class="modal-header">
                <h3>Código do Condomínio</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Código atual</label>
                    <input type="text" value="<?= esc($usuario['codigo'] ?? '') ?>" disabled>
                </div>
                <div class="input-group">
                    <label>Novo código</label>
                    <input type="text" name="valor" maxlength="4"
                        placeholder="4 dígitos"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <p style="font-size:13px; color:var(--cinza); margin-top:6px;">
                    Este código vincula sua conta ao condomínio selecionado.
                </p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Código</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR Foto de perfil
   ===================================================================== */
    elseif ($tipo === 'editar_img_perfil'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" enctype="multipart/form-data" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="imagem_perfil">

            <div class="modal-header">
                <h3>Alterar Foto de Perfil</h3>
            </div>

            <div class="modal-body" style="text-align: center;">
                <div class="input-group">
                    <label style="text-align: center; display: block; margin-bottom: 12px;">Sua nova foto</label>

                    <label class="upload-area avatar-upload">
                        <input type="file" name="imagem" id="idFotoPerfil" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewAvatar(this)" required>

                        <img id="avatarPreview" class="preview-imagem" src="" style="display: none;" alt="Preview da imagem">

                        <div class="upload-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--dourado);">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                                <circle cx="12" cy="13" r="4" />
                            </svg>
                            <span style="font-size: 11px; margin-top: 4px;">Escolher Foto</span>
                        </div>
                    </label>
                </div>
                <p style="font-size:13px; color:var(--cinza); margin-top:12px; text-align: center;">
                    Clique no círculo para selecionar o arquivo. Formatos aceitos: JPG ou PNG.
                </p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Nova Foto</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>
    <?php
    /* =====================================================================
        CRIAR AVISO (síndico)
        ===================================================================== */
    elseif ($tipo === 'aviso'): ?>
        <form action="../controls/avisos.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="criar">

            <div class="modal-header">
                <h3>Criar Aviso</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Título</label>
                    <input type="text" name="titulo" placeholder="Ex: Reunião de condomínio" required>
                </div>
                <div class="input-group">
                    <label>Data do Evento</label>
                    <input type="date" name="data_evento" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="input-group">
                    <label>Mensagem</label>
                    <textarea name="mensagem" class="comment-area" rows="4"
                        maxlength="500"
                        placeholder="Descreva o aviso..." required></textarea>
                    <div class="char-count">0 / 500</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Publicar Aviso</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   EDITAR AVISO
   id = id do aviso
   ===================================================================== */
    elseif ($tipo === 'editar_aviso'):
        $aviso = request("avisos?id=eq.$id");
        $a     = (!empty($aviso) && !isset($aviso['error'])) ? $aviso[0] : [];
    ?>
        <form action="../controls/avisos.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="id_aviso" value="<?= esc($id) ?>">
            <input type="hidden" name="campo" value="editar">

            <div class="modal-header">
                <h3>Editar Aviso</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Título</label>
                    <input type="text" name="titulo" value="<?= esc($a['titulo'] ?? '') ?>" required>
                </div>
                <div class="input-group">
                    <label>Data do Evento</label>
                    <input type="date" name="data_evento"
                        min="<?= date('Y-m-d') ?>"
                        value="<?= esc($a['data_evento'] ?? '') ?>" required>
                </div>
                <div class="input-group">
                    <label>Mensagem</label>
                    <textarea name="mensagem" class="comment-area" rows="4"
                        maxlength="500" required><?= esc($a['mensagem'] ?? '') ?></textarea>
                    <div class="char-count">0 / 500</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Alterações</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    /* =====================================================================
   APAGAR AVISO
   id = id do aviso
   ===================================================================== */
    elseif ($tipo === 'apagar_aviso'): ?>
        <form action="../controls/avisos.act.php" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="id_aviso" value="<?= esc($id) ?>">
            <input type="hidden" name="campo" value="apagar">

            <div class="modal-header">
                <h3>Apagar aviso?</h3>
            </div>

            <div class="modal-body">
                <p>O aviso será removido do mural e os moradores não terão mais acesso a ele. Tem certeza?</p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais btn-modais--danger">Sim, apagar</button>
                <button type="button" onclick="fecharModais()" class="btn-modais btn-modais--sec">Cancelar</button>
            </div>
        </form>

    <?php

    else: ?>
        <div class="modal-content modal-alerta">
            <div class="modal-header">
                <h3>Ops!</h3>
            </div>
            <div class="modal-body">
                <p>Tipo de modal desconhecido: <code><?= esc($tipo) ?></code></p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="fecharModais()" class="btn-modais">Fechar</button>
            </div>
        </div>

    <?php endif; ?>

</div>