<?php
session_start();
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-chat">
    <section class="chat-contatos">
        <?php
        $id_logado = $_SESSION['id'];
        $filtros = "or=(id_usuario1.eq.{$id_logado},id_usuario2.eq.{$id_logado})";
        $campos  = "*,id_usuario1(id,nome,imagem),id_usuario2(id,nome,imagem)";
        $ordem   = "criado_at.desc";

        $sql = "conversas?{$filtros}&select={$campos}&order={$ordem}";
        $abertas = request($sql, "GET");

        if ($abertas && !isset($abertas['error'])):
            foreach ($abertas as $aberta):
                if ($aberta['id_usuario1']['id'] == $id_logado) {
                    $contato = $aberta['id_usuario2'];
                } else {
                    $contato = $aberta['id_usuario1'];
                }

                $id_conversa  = $aberta['id'];
                $contato_id   = $contato['id'];
                $contato_nome = $contato['nome'];
                $contato_img  = !empty($contato['imagem']) ? $contato['imagem'] : './icon/user.png';
        ?>
                <div class="conversa-chat" onclick="abriChat(<?php echo $id_logado; ?>, <?php echo $contato_id; ?>, <?php echo $id_conversa; ?>)">
                    <img src="<?php echo $contato_img; ?>" alt="Perfil">
                    <p><?php echo $contato_nome; ?></p>
                </div>
        <?php
            endforeach;
        endif;
        ?>

        <div class="iniciar-msg">
            <button class="btn-inciar" style="width: 50px; height: 50px; font-size: 50px;">&#43;</button>
        </div>
    </section>
    <section class="chat-msg">
        <div class="mensagens">

        </div>
        <div class="barra-chat">
            <input type="text" placeholder="Digite sua mensagem...">
            <button class="btn-enviar" onclick="enviarMsg()">
                Enviar
            </button>
        </div>
    </section>
</main>

<!-- <form action="./controls/chat/listar_msg.php" method="post">
    <select name="enviar-pedido" id="">
        <option value="email"></option>
        <option value="telefone"></option>
        <option value="codigo-msg"></option>
    </select>

    <input type="text" name="contato" placeholder="Digite ...">
    <button type="submit">Enviar pedido</button>
</form> -->

<script>
    async function abriChat(id_logado, id_contato, id_conversa) {
        window.conversa_atual = id_conversa;
        window.contato_atual = id_contato;

        const caixaMensagens = document.querySelector('.mensagens');

        fetch(`./controls/chat/listar_msg.php?conversa=${id_conversa}`)
            .then(response => response.text())
            .then(html => {
                caixaMensagens.innerHTML = html;
                caixaMensagens.scrollTop = caixaMensagens.scrollHeight;
            })
            .catch(err => console.error("Erro ao carregar mensagens:", err));
    }

    function enviarMsg(){
        
    }
</script>