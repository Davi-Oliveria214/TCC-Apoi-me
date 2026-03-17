<?php
session_start();
require_once(__DIR__ . '/../../conexao.php');

$id_conversa = $_GET['conversa'];
$id_logado = $_SESSION['id'];

$sqlMensagem = "chat?id_conversa=eq.{$id_conversa}&select=*&order=criado_at.asc";

$mensagens = request($sqlMensagem, "GET");

if ($mensagens && !isset($mensagens['error'])) :
    foreach ($mensagens as $msg) :
        $classe = ($msg['id_autor'] == $id_logado) ? 'minhas-msg' : 'msg-contato';
?>
        <div class="item=msg <?php echo $classe ?>">
            <div class="balao">
                <p><?php echo $msg['texto'] ?></p>
                <small><?php echo date('H:i', strtotime($msg['criado_at'])); ?></small>
            </div>
        </div>
<?php
    endforeach;
else:
    echo "<p style='text-align:center; color:#888;'>Nenhuma mensagem aqui.</p>";
endif;