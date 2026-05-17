<div id="body-load"></div>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <a class="logo" href="../index.php">
                    <div class="logo-marca">A</div>
                    <span class="logo-texto">Apoie.me</span>
                </a>
                <p>Conectando suas necessidades ao seu Bem-estar</p>
            </div>

            <div class="footer-col">
                <h4>Institucional</h4>
                <ul>
                    <li><a href="../sobre.php">Sobre nós</a></li>
                    <li><a href="#">Parceiros</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Atendimento</h4>
                <ul>
                    <li><a href="../contato.php">Fale conosco</a></li>
                    <li><a href="mailto:apoie.me10@gmail.com">apoie.me10@gmail.com</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Área do cliente</h4>
                <ul>
                    <li><a href="../usuario.php">Minha conta</a></li>
                    <li><a href="../chat.php">Chat Online</a></li>
                    <?php if (empty($_SESSION['id'])) : ?><li><a href="../cadastro.php">Cadastre-se</a></li><?php endif; ?>
                    <li><a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './anunciar.php' ?>" class="btn-secundario">Anunciar serviço</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 Apoie.me</span>

        </div>
    </div>
</footer>

</body>

</html>