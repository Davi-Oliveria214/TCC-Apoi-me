<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');

$user_id = $_SESSION['id'];
$id_conversa_ativa = $_GET['id'] ?? null;

$conversas = request("conversas?or=(id_participante1.eq.$user_id,id_participante2.eq.$user_id)&order=ultima_atualizacao.desc", "GET");

$mensagens = [];
$conversa_ativa = null;
$outro_participante = null;
$bloqueado_por_mim = false;
$me_bloqueou = false;

if ($id_conversa_ativa) {
    $res_conversa = request("conversas?id=eq.$id_conversa_ativa", "GET");
    if (!empty($res_conversa) && !isset($res_conversa['error'])) {
        $conversa_ativa = $res_conversa[0];
        
        $id_outro = ($conversa_ativa['id_participante1'] == $user_id) ? $conversa_ativa['id_participante2'] : $conversa_ativa['id_participante1'];
        $res_outro = request("usuarios?id=eq.$id_outro&select=id,nome,imagem", "GET");
        if (!empty($res_outro)) {
            $outro_participante = $res_outro[0];
        }

        $mensagens = request("mensagens?id_conversa=eq.$id_conversa_ativa&order=id.asc", "GET");

        request("mensagens?id_conversa=eq.$id_conversa_ativa&id_autor=neq.$user_id&lida=eq.false", "PATCH", ['lida' => true]);

        $res_bloqueio_mim = request("bloqueios?id_bloqueador=eq.$user_id&id_bloqueado=eq.$id_outro", "GET");
        $bloqueado_por_mim = !empty($res_bloqueio_mim) && !isset($res_bloqueio_mim['error']);

        $res_bloqueio_outro = request("bloqueios?id_bloqueador=eq.$id_outro&id_bloqueado=eq.$user_id", "GET");
        $me_bloqueou = !empty($res_bloqueio_outro) && !isset($res_bloqueio_outro['error']);
    }
}
?>

<main class="pag-mensagens">
    <div class="ms-container">
        <aside class="ms-sidebar">
            <div class="ms-sidebar-header">
                <h2>Mensagens</h2>
            </div>
            <div class="ms-conversas-lista">
                <?php if (!empty($conversas) && !isset($conversas['error'])): 
                    foreach ($conversas as $conv):
                        $id_outro_lista = ($conv['id_participante1'] == $user_id) ? $conv['id_participante2'] : $conv['id_participante1'];
                        $res_user = request("usuarios?id=eq.$id_outro_lista&select=nome,imagem", "GET");
                        $user_lista = $res_user[0] ?? ['nome' => 'Usuário', 'imagem' => ''];
                        
                        // Buscar última mensagem
                        $res_last = request("mensagens?id_conversa=eq.{$conv['id']}&order=id.desc&limit=1", "GET");
                        $last_msg = $res_last[0] ?? null;
                        
                        $res_unread = request("mensagens?id_conversa=eq.{$conv['id']}&id_autor=neq.$user_id&lida=eq.false&select=count", "GET");

                        $unread_count = 0;
                        
                        $ativa = ($id_conversa_ativa == $conv['id']) ? 'ativa' : '';
                ?>
                        <a href="mensagens.php?id=<?= $conv['id'] ?>" class="ms-conversa-item <?= $ativa ?>">
                            <div class="ms-user-img">
                                <?php if (!empty($user_lista['imagem'])): ?>
                                    <img src="<?= htmlspecialchars($user_lista['imagem']) ?>" alt="<?= htmlspecialchars($user_lista['nome']) ?>">
                                <?php else: ?>
                                    <div class="ms-user-placeholder"><?= substr($user_lista['nome'], 0, 1) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="ms-conversa-info">
                                <div class="ms-conversa-topo">
                                    <strong><?= htmlspecialchars($user_lista['nome']) ?></strong>
                                    <span><?= $conv['ultima_atualizacao'] ? date('H:i', strtotime($conv['ultima_atualizacao'])) : '' ?></span>
                                </div>
                                <p><?= $last_msg ? htmlspecialchars(substr($last_msg['conteudo'], 0, 30)) . (strlen($last_msg['conteudo']) > 30 ? '...' : '') : 'Inicie uma conversa' ?></p>
                            </div>
                        </a>
                <?php endforeach; 
                else: ?>
                    <div class="ms-vazio-lista">
                        <p>Nenhuma conversa encontrada.</p>
                    </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Área do Chat -->
        <section class="ms-chat">
            <?php if ($conversa_ativa && $outro_participante): ?>
                <div class="ms-chat-header">
                    <div class="ms-chat-user">
                        <div class="ms-user-img">
                            <?php if (!empty($outro_participante['imagem'])): ?>
                                <img src="<?= htmlspecialchars($outro_participante['imagem']) ?>" alt="<?= htmlspecialchars($outro_participante['nome']) ?>">
                            <?php else: ?>
                                <div class="ms-user-placeholder"><?= substr($outro_participante['nome'], 0, 1) ?></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3><?= htmlspecialchars($outro_participante['nome']) ?></h3>
                        </div>
                    </div>
                    <div class="ms-chat-acoes">
                        <button class="ms-btn-icon" onclick="window.location.reload()" title="Atualizar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                        </button>
                        <div class="ms-dropdown">
                            <button class="ms-btn-icon" onclick="toggleDropdown('ms-menu-opcoes')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                            </button>
                            <div id="ms-menu-opcoes" class="ms-dropdown-content">
                                <?php if ($bloqueado_por_mim): ?>
                                    <form action="controls/mensagens.act.php" method="POST">
                                        <input type="hidden" name="acao" value="desbloquear">
                                        <input type="hidden" name="id_bloqueado" value="<?= $outro_participante['id'] ?>">
                                        <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                        <button type="submit">Desbloquear Usuário</button>
                                    </form>
                                <?php else: ?>
                                    <form action="controls/mensagens.act.php" method="POST" onsubmit="return confirm('Bloquear este usuário?')">
                                        <input type="hidden" name="acao" value="bloquear">
                                        <input type="hidden" name="id_bloqueado" value="<?= $outro_participante['id'] ?>">
                                        <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                        <button type="submit">Bloquear Usuário</button>
                                    </form>
                                <?php endif; ?>
                                <form action="controls/mensagens.act.php" method="POST" onsubmit="return confirm('Excluir esta conversa permanentemente?')">
                                    <input type="hidden" name="acao" value="excluir_conversa">
                                    <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                    <button type="submit" class="danger">Excluir Conversa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ms-mensagens-container" id="chat-container">
                    <?php if (!empty($mensagens) && !isset($mensagens['error'])): 
                        foreach ($mensagens as $msg):
                            $sou_autor = ($msg['id_autor'] == $user_id);
                            $classe_msg = $sou_autor ? 'ms-msg-enviada' : 'ms-msg-recebida';
                    ?>
                            <div class="ms-mensagem <?= $classe_msg ?>">
                                <div class="ms-msg-bolha">
                                    <p><?= nl2br(htmlspecialchars($msg['conteudo'])) ?></p>
                                    <div class="ms-msg-meta">
                                        <span><?= date('H:i', strtotime($msg['hora'])) ?></span>
                                        <?php if ($sou_autor): ?>
                                            <form action="controls/mensagens.act.php" method="POST" class="ms-form-apagar">
                                                <input type="hidden" name="acao" value="apagar_mensagem">
                                                <input type="hidden" name="id_mensagem" value="<?= $msg['id'] ?>">
                                                <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                                <button type="submit" title="Apagar" onclick="return confirm('Apagar esta mensagem?')">×</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; 
                    else: ?>
                        <div class="ms-vazio-chat">
                            <p>Diga olá para iniciar a conversa!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="ms-chat-footer">
                    <?php if ($bloqueado_por_mim || $me_bloqueou): ?>
                        <div class="ms-bloqueio-aviso">
                            <p><?= $bloqueado_por_mim ? 'Você bloqueou este usuário.' : 'Este usuário bloqueou você.' ?></p>
                        </div>
                    <?php else: ?>
                        <form action="controls/mensagens.act.php" method="POST" class="ms-envio-form">
                            <input type="hidden" name="acao" value="enviar">
                            <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                            <textarea name="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                            <button type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="ms-vazio-selecao">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <h3>Selecione uma conversa</h3>
                    <p>Escolha um participante na lista ao lado para começar.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<script>
    // Scroll para o fim das mensagens
    const container = document.getElementById('chat-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    function toggleDropdown(id) {
        const el = document.getElementById(id);
        el.classList.toggle('show');
    }

    // Fechar dropdown ao clicar fora
    window.onclick = function(event) {
        if (!event.target.matches('.ms-btn-icon') && !event.target.closest('.ms-btn-icon')) {
            const dropdowns = document.getElementsByClassName("ms-dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

<?php include "./includes/rodape.php"; ?>
