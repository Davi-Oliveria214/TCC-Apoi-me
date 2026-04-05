<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-chat">
    <section class="chat-contatos">
        <div class="contatos">
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
                    <div class="usuarios-chat" onclick="abriChat(<?php echo $id_logado; ?>, <?php echo $contato_id; ?>, <?php echo $id_conversa; ?>)">
                        <img src="<?php echo $contato_img; ?>" alt="Perfil">
                        <p><?php echo $contato_nome; ?></p>
                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
        <div class="iniciar-msg">
            <button class="btn-inciar" style="width: 50px; height: 50px; font-size: 50px;" onclick="addPedido()">&#43;</button>
        </div>
    </section>
    <section class="chat-msg">
        <button onclick="voltarContatos()" class="btn-voltar">←</button>
        <div class="mensagens">

        </div>
        <div class="barra-chat">
            <input type="text" id="mensagem-texto" placeholder="Digite sua mensagem...">
            <button class="btn-enviar" onclick="enviarMsg()">
                Enviar
            </button>
        </div>
    </section>
</main>


<form action="./controls/mensagens/add_msg.php" method="post" id="adicionar" style="display: none;">
    <select name="enviar-pedido" id="">
        <option value="email">Email</option>
        <option value="telefone">Telefone</option>
    </select>

    <input type="text" name="contato" placeholder="Digite ...">
    <button type="submit">Enviar pedido</button>
</form>


<script>
    function abriChat(id_logado, id_contato, id_conversa) {
        window.conversa_atual = id_conversa;
        window.contato_atual = id_contato;

        const caixaMensagens = document.querySelector('.mensagens');

        fetch(`./controls/mensagens/listar_msg.php?conversa=${id_conversa}`)
            .then(response => response.text())
            .then(html => {
                caixaMensagens.innerHTML = html;
                caixaMensagens.scrollTop = caixaMensagens.scrollHeight;

                if (window.innerWidth <= 768) {
                    document.querySelector('.chat-contatos').classList.add('escondido');
                    document.querySelector('.chat-msg').classList.add('ativo');
                }
            });
    }

    function enviarMsg() {
        const campo = document.getElementById('mensagem-texto');
        const texto = campo.value;
        const id_conversa = window.conversa_atual;

        if (!texto || !id_conversa) return;

        const dados = new FormData();
        dados.append('id_conversa', id_conversa);
        dados.append('texto', texto);

        fetch('./controls/mensagens/enviar_msg.php', {
                method: 'POST',
                body: dados
            })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.success) {
                    campo.value = '';
                    abriChat(null, null, id_conversa);
                }
            })
            .catch(err => console.error("Erro ao enviar:", err));
    }

    function addPedido() {
        document.getElementById('adicionar').style.display = "flex";

        let overlay = document.createElement('div');
        overlay.id = "overlay";
        overlay.onclick = () => {
            overlay.remove();
            document.getElementById('adicionar').style.display = "none";
        };

        document.body.appendChild(overlay);
    }

    function voltarContatos() {
        document.querySelector('.chat-contatos').classList.remove('escondido');
        document.querySelector('.chat-msg').classList.remove('ativo');
    }

    function addPedido() {
        document.getElementById('adicionar').style.display = "flex";

        let overlay = document.createElement('div');
        overlay.id = "overlay";
        overlay.onclick = () => {
            overlay.remove();
            document.getElementById('adicionar').style.display = "none";
        };

        document.body.appendChild(overlay);
    }
</script>