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

        <form action="./controls/login.act.php" method="post">

            <div class="campo">
                <label for="idChave">Chave de acesso</label>
                <div class="campo-input-wrap">
                    <img class="icone-campo" src="./icon/chave.png" alt="Chave">
                    <input class="com-icone" type="text" name="chave" id="idChave"
                        onkeydown="if(event.key === ' ') event.preventDefault()"
                        placeholder="Digite sua chave" required autocomplete="off">
                </div>
                <span class="campo-hint">Chave fornecida pelo seu condomínio</span>
            </div>

            <div class="campo">
                <label for="idEmail">E-mail</label>
                <div class="campo-input-wrap">
                    <img class="icone-campo" src="./icon/email.png" alt="E-mail">
                    <input class="com-icone" type="email" name="email" id="idEmail"
                        onkeydown="if(event.key === ' ') event.preventDefault()"
                        placeholder="seu@email.com" required autocomplete="email">
                </div>
            </div>

            <div class="campo">
                <label for="idSenha">Senha</label>
                <div class="campo-input-wrap">
                    <img class="icone-campo" src="./icon/lock.png" alt="Senha">
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