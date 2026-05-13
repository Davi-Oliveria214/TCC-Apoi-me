<div id="body-load"></div>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <a class="logo" href="#">
                    <div class="logo-marca">A</div>
                    <span class="logo-texto">Apoie.me</span>
                </a>
                <p>Conectando necessidades a talentos dentro do seu condomínio, promovendo uma economia local segura e eficiente.</p>
            </div>

            <div class="footer-col">
                <h4>Institucional</h4>
                <ul>
                    <li><a href="#">Sobre nós</a></li>
                    <li><a href="#">Como funciona</a></li>
                    <li><a href="#">Parceiros</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Atendimento</h4>
                <ul>
                    <li><a href="#">Fale conosco</a></li>
                    <li><a href="#">apoie.me10@gmail.com</a></li>
                    <li><a href="#">Suporte</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Área do cliente</h4>
                <ul>
                    <li><a href="#">Minha conta</a></li>
                    <li><a href="#">Cadastre-se</a></li>
                    <li><a href="#">Meus agendamentos</a></li>
                    <li><a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './anunciar.php' ?>" class="btn-secundario">Anunciar serviço</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 Apoie.me</span>
            <span>Desenvolvido com AMOR para condomínios</span>
        </div>
    </div>
</footer>

</body>

</html>