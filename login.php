<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<main id="principal-logar-cadastro">
    <form action="" class="centro-cadastro-login">
        <h1>Faça seu Login</h1>

        <div class="card-login-cadastro">
            <div class="info-cadastro-login">
                <label for="idChave">Chave de acesso</label>
                <input type="text" id="idChave" placeholder="Chave de acesso" required>
            </div>

            <div class="info-cadastro-login">
                <label for="idEmail">Email</label>
                <input type="email" id="idEmail" placeholder="Email" required>
            </div>

            <div class="info-cadastro-login">
                <label for="idSenha">Senha</label>
                <input type="password" id="idSenha" placeholder="Senha" required>
            </div>
        </div>

        <div class="entrar-cadastrar">
            <button type="submit" class="botao btn-cadastrar">Logar</button>
            <button type="submit" class="botao btn-cadastrar">Cadastrar</button>
        </div>
    </form>
</main>

<footer class="rodape">
    <ul>
        <li>Capital São Paulo:<p>(11) 1111-1111</p>
        </li>
        <li>Demais locais:<p>0000 000 0000</p>
        </li>
        <li>Atendimento de ligações:<p>XXh as XXh</p>
        </li>
        <li>WhatsApp:<p>(11) 2222-2222</p>
        </li>
        <li>Email:<p>conato@gmail.com</p>
        </li>
    </ul>

    <div class="redes-socias">
        <img src="img/instagram-icon.png" alt="">
        <img src="img/tiktok-icon.png" alt="">
        <img src="img/youtube-icon.png" alt="">
    </div>
</footer>
<?php
include "./includes/rodape.php";
?>