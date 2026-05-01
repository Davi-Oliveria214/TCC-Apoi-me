<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo('GET');

require_once(__DIR__ . '/../conexao.php');

$tipo = $_GET['tipo'] ?? '';
$id_registro = $_GET['id_registro'] ?? '';
$id_contrato = $_GET['id_contrato'] ?? '';
$nome_servico = $_GET['nome_servico'] ?? '';
$desc = $_GET['desc'] ?? '';
$img_servico = $_GET['img_servico'] ?? '';
$data = $_GET['data'] ?? '';
$comentario = $_GET['comentario'] ?? '';
$hora_inicio = $_GET['hora_inicio'] ?? '';
$hora_fim = $_GET['hora_fim'] ?? '';
$duracao = $_GET['duracao'] ?? '';
$status = $_GET['status'] ?? '';
$ativo = $_GET['ativo'] ?? '';
$nota = $_GET['nota'] ?? '';
$observacao = $_GET['observacao'] ?? '';

$morador = request("usuarios?id=eq.{$_SESSION['id']}&select=nome,email,codigo");
if ($tipo == 'horarios') {

    $dataSelecionada = $_GET['data'];
    $id_servico = $_GET['id_registro'];

    $servico = request("servicos?id=eq.$id_servico")[0];

    $hora_inicio = $servico['hora_inicio'];
    $hora_fim = $servico['hora_fim'];
    $duracao = strtotime($servico['duracao']);

    $duracaoMin = (int) date('i', $duracao) + (int) date('H', $duracao) * 60;

    $reservas = request("contratados?dia=eq.$dataSelecionada&id_servico=eq.$id_servico");

    $ocupados = [];
    if (!empty($reservas) && !isset($reservas['error'])) {
        foreach ($reservas as $r) {
            $ocupados[] = date('H:i', strtotime($r['hora']));
        }
    }

    $inicio = strtotime($hora_inicio);
    $fim = strtotime($hora_fim);
    $intervalo = max(1, $duracaoMin) * 60;

    for ($i = $inicio; $i <= $fim; $i += $intervalo):
        $hora = date("H:i", $i);
        $bloqueado = in_array($hora, $ocupados);
?>
        <option value="<?php echo $hora ?>" <?php echo $bloqueado ? 'disabled' : '' ?>>
            <?php echo $hora ?> <?php echo $bloqueado ? '(Indisponível)' : '' ?>
        </option>
<?php
    endfor;

    exit;
}
?>

<div class="modal-overlay" style="display: flex;">

    <?php if ($tipo == 'agendar'): ?>
        <form action="../controls/agendar.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id_registro ?>">

            <div class="modal-header">
                <h3>Agendar: <?php echo $nome_servico ?></h3>
            </div>

            <div class="modal-body">
                <img src="<?php echo $img_servico ?>" class="modal-img-destaque">
                <div class="input-row">
                    <div class="input-group">
                        <label>Data</label>
                        <input type="date" name="data" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="horarios">Hora</label>
                        <select name="hora" id="horarios" required>
                            <option value="">Selecione uma data</option>
                        </select>
                        <small class="helper-text">
                            Disponível entre <?php echo $hora_inicio; ?> e <?php echo $hora_fim; ?>
                        </small>
                    </div>

                    <datalist id="horario_duracao">
                        <?php
                        $inicio = strtotime($hora_inicio);
                        $fim = strtotime($hora_fim);
                        $intervalo = $duracao * 60;

                        for ($i = $inicio; $i <= $fim; $i += $intervalo):
                            $horaFormatada = date("H:i", $i);
                        ?>
                            <option value="<?php echo $horaFormatada ?>">
                                <?php echo $horaFormatada ?>
                            </option>
                        <?php endfor; ?>
                    </datalist>
                </div>
                <div class="input-group">
                    <label>Observações</label>
                    <textarea name="observacao" rows="3" placeholder="Algo que o prestador precise saber?"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Confirmar Agendamento</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Voltar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'cancelar' || $tipo == 'excluir') :
        $isExcluir = ($tipo == 'excluir');
        $action = $isExcluir ? "./controls/excluir.php" : "./controls/cancelar.php";
    ?>
        <form action="<?php echo $action ?>" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="<?php echo $isExcluir ? 'id_servico' : 'resp' ?>" value="<?php echo $id_registro ?>">

            <div class="modal-header">
                <h3><?php echo $isExcluir ? 'Excluir Serviço?' : 'Cancelar?' ?></h3>
            </div>

            <div class="modal-body">
                <p><?php echo $isExcluir
                        ? 'Esta ação é permanente. Todas as reservas futuras vinculadas serão removidas.'
                        : 'Tem certeza que deseja cancelar esta solicitação?' ?></p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Confirmar</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Voltar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'avaliar'): ?>

        <form action="./controls/avaliarServico.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">
            <input type="hidden" name="id_contrato" value="<?php echo $id_contrato ?>">

            <div class="modal-header">
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
                <button type="submit" class="btn-modais">Enviar Avaliação</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar' || $tipo == 'novo') :
        $isEdit = ($tipo == 'editar');
        $action = $isEdit ? "./controls/editar_servico.act.php" : "./controls/addServico.php";
    ?>
        <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" class="modal-content modal-padrao modal-large ativar-load">
            <input type="hidden" value="<?php echo $id_registro ?>" name="id_servico">

            <div class="modal-header">
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
                            <?php
                            $categorias = request("categorias?select=id,nome&order=nome.asc", "GET");
                            foreach ($categorias as $categoria) :
                            ?>
                                <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="input-row">
                    <div class="input-group">
                        <label for="hora_inicio">Início</label>
                        <input type="time" name="hora_inicio" value="<?php echo $hora_inicio ?>" list="horarios-comuns" required>
                    </div>

                    <div class="input-group">
                        <label for="hora_fim">Término</label>
                        <input type="time" name="hora_fim" value="<?php echo $hora_fim ?>" list="horarios-comuns" required>
                    </div>

                    <datalist id="horarios-comuns">
                        <option value="08:00">
                        <option value="09:00">
                        <option value="10:00">
                        <option value="11:00">
                        <option value="12:00">
                        <option value="13:00">
                        <option value="14:00">
                        <option value="15:00">
                        <option value="16:00">
                        <option value="17:00">
                        <option value="18:00">
                    </datalist>
                </div>

                <div class="input-group">
                    <label>Tempo de duração</label>
                    <input type="time" name="duracao" value="<?php echo $duracao ?>" list="tempos-comuns" required>

                    <datalist id="tempos-comuns">
                        <option value="00:30">
                        <option value="01:00">
                        <option value="01:30">
                        <option value="02:00">
                        <option value="02:30">
                        <option value="03:00">
                    </datalist>
                </div>

                <div class="input-group">
                    <label>Descrição</label>
                    <textarea name="descricao" rows="4" required><?php echo $desc ?></textarea>
                </div>

                <div class="input-group">
                    <label>Imagem do Serviço</label>
                    <label for="idImagem" class="upload-area">
                        <img id="preview" class="preview-imagem" src="<?php echo $img_servico ?>">
                        <div class="upload-overlay"><span>Alterar Foto</span></div>
                    </label>
                    <input type="file" name="imagem" id="idImagem" class="input-imagem" accept="image/*" style="display: none;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais"><?php echo $isEdit ? 'Salvar Alterações' : 'Publicar Anúncio' ?></button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'ver_avaliacao'): ?>

        <div class="modal-content modal-padrao">
            <div class="modal-header">
                <h3>Avaliação do Serviço</h3>
            </div>

            <div class="modal-body">
                <div class="mini-card-servico">
                    <img src="<?php echo $img_servico ?>">
                    <div>
                        <strong><?php echo $nome_servico ?></strong>
                    </div>
                </div>

                <div style="margin-top:15px;">
                    <?php
                    $nota = (int) $_GET['nota'];
                    echo str_repeat("★", $nota);
                    ?>
                </div>

                <div style="margin-top:10px;">
                    <strong>Comentário:</strong>
                    <p><?php echo !empty($comentario) ? $comentario : 'Sem comentário.' ?></p>
                </div>
            </div>

            <div class="modal-footer">
                <button onclick="fecharModais()" class="btn-modais">Fechar</button>
            </div>
        </div>
    <?php elseif ($tipo == 'pausar'): ?>

        <?php
        $ativoAtual = ($ativo === 'true' || $ativo === '1');
        $novoStatus = $ativoAtual ? false : true;
        ?>

        <form action="./controls/pausar.act.php" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="id_servico" value="<?php echo $id_registro ?>">
            <input type="hidden" name="status" value="<?php echo $novoStatus ? 'true' : 'false' ?>">

            <div class="modal-header">
                <h3><?php echo $ativoAtual ? 'Pausar serviço?' : 'Ativar serviço?' ?></h3>
            </div>

            <div class="modal-body">
                <p>
                    <?php echo $ativoAtual
                        ? 'O serviço ficará indisponível para novos agendamentos.'
                        : 'O serviço voltará a ficar disponível para agendamentos.' ?>
                </p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">
                    <?php echo $ativoAtual ? 'Pausar' : 'Ativar' ?>
                </button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar_nome'): ?>

        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="nome">

            <div class="modal-header">
                <h3>Alterar Nome</h3>
            </div>

            <div class="modal-body">
                <p>
                    Como você gostaria de ser chamado na plataforma?
                </p>
                <div class="input-group">
                    <label>Nome atual</label>
                    <input type="text" value="<?php echo $morador[0]['nome'] ?>" disabled>
                </div>
                <div class="input-group">
                    <label>Novo nome</label>
                    <input type="text" name="valor" placeholder="Digite o novo nome" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Nome</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar_email'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="email">

            <div class="modal-header">
                <h3>Alterar E-mail</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Email atual</label>
                    <input type="email" value="<?php echo $morador[0]['email']  ?>" disabled>
                </div>
                <div class="input-group">
                    <label>Novo Email</label>
                    <input type="email" name="valor" placeholder="exemplo@email.com" required>
                </div>
                <p>Você precisará usar este novo e-mail no seu próximo login.</p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Atualizar E-mail</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar_senha'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="senha">

            <div class="modal-header">
                <h3>Alterar Senha</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Senha Atual</label>
                    <input type="password" name="senha_atual" placeholder="Digite sua senha atual" required>
                </div>

                <div class="input-group">
                    <label for="idSenha">Nova Senha</label>
                    <div class="input-container">
                        <input type="password" name="nova_senha" id="idSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Senha" required>
                        <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idSenha', this)">
                    </div>
                    <p class="texto-senha" style="color: var(--verde-musgo-medio);"></p>
                </div>

                <div class="input-group">
                    <label for="idRptSenha">Confirmar Nova Senha</label>
                    <div class="input-container">
                        <input type="password" name="confirmar_senha" id="idRptSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Repita senha" required>
                        <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idRptSenha', this)">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnEnviar" class="btn-modais">Redefinir Senha</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar_codigo'): ?>
        <form action="../controls/editar_perfil.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="codigo">

            <div class="modal-header">
                <h3>Código do Condomínio</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Chave de Acesso atual</label>
                    <input type="text" value="<?php echo $morador[0]['codigo'] ?? '' ?>">
                </div>
                <div class="input-group">
                    <label>Chave de Acesso</label>
                    <input type="text" name="valor" maxlength="4" placeholder="Digite o código" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>
                <p>
                    Este código vincula sua conta ao condomínio selecionado.
                </p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Código</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>
    <?php elseif ($tipo == 'aviso'): ?>

        <form action="./controls/avisos.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="campo" value="criar">
            <div class="modal-header">
                <h3>Criar Aviso</h3>
            </div>

            <div class="modal-body">

                <div class="input-group">
                    <label>Título</label>
                    <input type="text" name="titulo" required>
                </div>

                <div class="input-group">
                    <label>Data do Evento</label>
                    <input type="date" name="data_evento" min="<?php echo date('Y-m-d') ?>" required>
                </div>

                <div class="input-group">
                    <label>Mensagem</label>
                    <textarea name="mensagem" class="comment-area" rows="4" maxlength="500" placeholder="Descreva o aviso..." required></textarea>
                    <div class="char-count">0 / 500</div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Publicar Aviso</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'editar_aviso'): ?>

        <?php
        $avisoAtual = request("avisos?id=eq.$id_registro");
        $avisoAtual = (!empty($avisoAtual) && !isset($avisoAtual['error'])) ? $avisoAtual[0] : null;

        $tituloAviso = $avisoAtual['titulo'] ?? '';
        $dataAviso = $avisoAtual['data_evento'] ?? '';
        $mensagemAviso = $avisoAtual['mensagem'] ?? '';
        ?>

        <form action="./controls/avisos.act.php" method="post" class="modal-content modal-padrao ativar-load">
            <input type="hidden" name="id_aviso" value="<?php echo $id_registro ?>">
            <input type="hidden" name="campo" value="editar">

            <div class="modal-header">
                <h3>Editar Aviso</h3>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label>Título</label>
                    <input type="text" name="titulo" value="<?php echo $tituloAviso ?>" required>
                </div>

                <div class="input-group">
                    <label>Data do Evento</label>
                    <input type="date" name="data_evento" min="<?php echo date('Y-m-d') ?>" value="<?php echo $dataAviso ?>" required>
                </div>

                <div class="input-group">
                    <label>Mensagem</label>
                    <textarea name="mensagem" class="comment-area" rows="4" maxlength="500" required><?php echo $mensagemAviso ?></textarea>
                    <div class="char-count">0 / 500</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Salvar Alterações</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>

    <?php elseif ($tipo == 'apagar_aviso'): ?>

        <form action="./controls/avisos.act.php" method="post" class="modal-content modal-alerta ativar-load">
            <input type="hidden" name="id_aviso" value="<?php echo $id_registro ?>">
            <input type="hidden" name="campo" value="apagar">

            <div class="modal-header">
                <h3>Apagar Aviso?</h3>
            </div>

            <div class="modal-body">
                <p>O aviso será removido do mural e os moradores não terão mais acesso a ele. Tem certeza que deseja continuar?</p>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modais">Sim, Apagar</button>
                <button type="button" onclick="fecharModais()" class="btn-modais">Cancelar</button>
            </div>
        </form>
    <?php else : ?>
        <div class="modal-content modal-padrao">
            <div class="modal-header">
                <h3>Detalhes do Agendamento</h3>
            </div>
            <div class="modal-body">
                <img src="<?php echo $img_servico ?>" class="modal-img-destaque">
                <div class="detalhes-lista">
                    <div class="detalhe-item">
                        <label>Serviço:</label>
                        <p><?php echo $nome_servico ?></p>
                    </div>
                    <div class="detalhe-item">
                        <label>Descrição:</label>
                        <p><?php echo $desc ?></p>
                    </div>
                    <div class="input-row">
                        <div class="detalhe-item">
                            <label>Data:</label>
                            <p><?php echo date('d/m/Y', strtotime($data)) ?></p>
                        </div>
                        <div class="detalhe-item">
                            <label>Hora:</label>
                            <p><?php echo $hora_inicio ?></p>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="detalhe-item">
                            <label>Status:</label>
                            <p class="status-badge"><?php echo $status ?></p>
                        </div>
                        <div class="detalhe-item">
                            <label>Observação:</label>
                            <p class="status-badge"><?php echo $observacao ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="fecharModais()" class="btn-modais">Entendi</button>
            </div>
        </div>
    <?php endif ?>

</div>