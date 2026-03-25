<footer class="rodape">
    <ul>
        <li onclick="abrir_info(1)"><b>Institucional</b>
            <p>Sobre a empresa <br> Parceiros <br> Politica de privacidade </p>
        </li>
        <li onclick="abrir_info(2)"><b>Atendimento</b>
            <p>Fale Conosco <br> apoie_mi@gmail.com <br> (11) 1111-1111 </p>
        </li>
        <li onclick="abrir_info(3)"><b>Área do cliente</b>
            <p>Minha Conta <br> Chat Online <br> <?php echo isset($_SESSION['login']) ? '' : '<a href="../cadastro.php">Cadastre-se</a>' ?></p>
        </li>
    </ul>
</footer>

<ul id="institucional" class="invisivel" onclick="fechar(1)">
    <li onclick="event.stopPropagation()"> <b>Institucional</b>
        <p><a href="../sobre.php">Sobre a empresa</a><br>Parceiros<br>Politica de privacidade</p>
    </li>
</ul>

<ul id="atendimento" class="invisivel" onclick="fechar(2)">
    <li onclick="event.stopPropagation()"><b>Atendimento</b>
        <p><a href="../contato.php">Fale Conosco</a> <br> apoie_mi@gmail.com <br> (11) 1111-1111 </p>
    </li>
</ul>

<ul id="cliente" class="invisivel" onclick="fechar(3)">
    <li onclick="event.stopPropagation()">
        <b>Área do cliente</b>
        <p>Minha Conta<br> <a href="<?php echo empty($_SESSION['login']) ? '../login.php' : '../mensagens.php' ?>">Chat Online</a> <br> <?php echo isset($_SESSION['login']) ? '' : '<a href="../cadastro.php">Cadastre-se</a>' ?></p>
    </li>
</ul>

</body>

</html>