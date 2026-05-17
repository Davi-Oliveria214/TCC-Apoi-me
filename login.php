<?php
require_once './includes/funcoes.php';
loginFeito();
?>
<?php include_once './includes/head.php'; ?>
<?php include_once './includes/topo.php' ?>

<div class="painel-visual">
    <div class="painel-visual-bg"></div>

    <div class="painel-topo"></div>

    <div class="painel-rodape">
        <blockquote>A solução mora<br><em>ao seu lado</em></blockquote>
        <p>Conectamos moradores que precisam de serviços com profissionais do seu condomínio. Rápido, seguro e de confiança.</p>
        <div class="dots">
            <div class="dot ativo"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
</div>

<div class="painel-form">
    <div class="form-wrapper">

        <div class="form-header">
            <span class="subtag">Bem-vindo de volta</span>
            <h1>Entrar na sua conta</h1>
            <p>Acesse para contratar ou gerenciar seus serviços no condomínio.</p>
        </div>

        <form action="./controls/auth.act.php" method="post">
            <input type="hidden" name="acao" value="login">

            <div class="campo">
                <label for="idChave">Chave de acesso</label>
                <div class="campo-input-wrap">
                    <svg class="icone-campo" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <input class="com-icone" type="text" name="chave" id="idChave"
                        onkeydown="if(event.key === ' ') event.preventDefault()"
                        placeholder="Digite sua chave" required autocomplete="off">
                </div>
                <span class="campo-hint">Chave fornecida pelo seu condomínio</span>
            </div>

            <div class="campo">
                <label for="idEmail">E-mail</label>
                <div class="campo-input-wrap">
                    <svg class="icone-campo" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <input class="com-icone" type="email" name="email" id="idEmail"
                        onkeydown="if(event.key === ' ') event.preventDefault()"
                        placeholder="seu@email.com" required autocomplete="email">
                </div>
            </div>

            <div class="campo">
                <label for="idSenha">Senha</label>
                <div class="campo-input-wrap">
                    <svg class="icone-campo" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <input class="com-icone" type="password" name="senha" id="idSenha"
                        onkeydown="if(event.key === ' ') event.preventDefault()"
                        placeholder="Sua senha" required autocomplete="current-password">
                    <button type="button" class="olho-btn" onclick="toggleSenha('idSenha', this)" aria-label="Mostrar senha">
                        <img id="olho-idSenha" src="./icon/visibility.png" alt="Mostrar">
                    </button>
                </div>
            </div>

            <div class="linha-extra">
                <a href="./verificar_acesso.php?etapa=enviar" class="link-sutil">Esqueci minha senha</a>
            </div>

            <button type="submit" class="btn-submit">
                Entrar na conta
            </button>

        </form>

        <div class="divisor">
            <div class="divisor-linha"></div>
            <span>Não tem uma conta?</span>
            <div class="divisor-linha"></div>
        </div>

        <p class="form-footer-txt">
            <a href="./cadastro.php">Criar conta gratuita →</a>
        </p>
    </div>
</div>

</div>

<?php include_once './includes/rodape.php'; ?>