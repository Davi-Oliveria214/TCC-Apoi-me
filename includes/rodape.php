<div id="body-load"></div>

<footer class="rodape">
    <ul>
        <li><b>Institucional</b>
            <p><a href="./sobre.php">Sobre a empresa</a><br><a href="#publicidade">Parceiros</a><br>Politica de privacidade</p>
        </li>
        <li><b>Atendimento</b>
            <p><a href="./contato.php">Fale Conosco</a><br>apoie_mi@gmail.com</p>
        </li>
        <li><b>Área do cliente</b>
            <p><a href="<?php echo !empty($_SESSION['login']) ? './usuario.php' : './login.php' ?>">Minha Conta</a><br><a href="<?php echo !empty($_SESSION['login']) ? './mensagens.php' : './login.php' ?>">Chat Online</a><br> <?php echo isset($_SESSION['login']) ? '' : '<a href="../cadastro.php">Cadastre-se</a>' ?></p>
        </li>
    </ul>
</footer>

</body>

</html>